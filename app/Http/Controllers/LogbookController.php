<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    /**
     * Store a newly created logbook entry (by Mahasiswa).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'content' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subject / Topik kegiatan wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan wajib dipilih.',
            'content.required' => 'Uraian aktivitas (long text) wajib diisi.',
            'content.min' => 'Uraian aktivitas minimal 10 karakter.',
        ]);

        $user = Auth::user();

        // Get student's assigned lecturer, or fallback to the first dosen pembimbing in database
        $dosenId = $user->dosen_id;
        if (! $dosenId) {
            $defaultDosen = User::where('role', 'dosen')->first();
            $dosenId = $defaultDosen ? $defaultDosen->id : null;

            // Link student to this lecturer if not yet assigned
            if ($dosenId) {
                $user->dosen_id = $dosenId;
                $user->save();
            }
        }

        $now = now();
        $logbook = Logbook::create([
            'user_id' => $user->id,
            'dosen_id' => $dosenId,
            'subject' => $validated['subject'],
            'tanggal' => $validated['tanggal'] ?? $now->format('Y-m-d'),
            'content' => $validated['content'],
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil disimpan pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB dan siap ditinjau oleh Dosen Pembimbing.',
                'logbook' => $logbook->load('student', 'dosen'),
            ]);
        }

        return redirect()->back()->with('success', 'Logbook berhasil dikirim pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB dan siap ditinjau oleh Dosen Pembimbing!');
    }

    /**
     * Display a specific logbook details (useful for modal / ajax view).
     */
    public function show(Logbook $logbook)
    {
        $logbook->load('student', 'dosen');

        return response()->json([
            'success' => true,
            'logbook' => [
                'id' => $logbook->id,
                'subject' => $logbook->subject,
                'tanggal' => $logbook->tanggal->format('Y-m-d'),
                'tanggal_formatted' => $logbook->tanggal->translatedFormat('d F Y'),
                'content' => $logbook->content,
                'status' => $logbook->status,
                'feedback' => $logbook->feedback,
                'student_name' => $logbook->student ? $logbook->student->name : 'Mahasiswa',
                'student_nim' => $logbook->student ? $logbook->student->identifier : '-',
                'student_prodi' => $logbook->student ? $logbook->student->prodi : 'Teknik Informatika',
                'created_at_formatted' => $logbook->created_at ? $logbook->created_at->diffForHumans() : '-',
            ],
        ]);
    }

    /**
     * Update an existing logbook entry (by Mahasiswa).
     */
    public function update(Request $request, Logbook $logbook)
    {
        $user = Auth::user();

        // Ensure only the student owner can edit their logbook
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah logbook ini.');
        }

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'content' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subject / Topik kegiatan wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan wajib dipilih.',
            'content.required' => 'Uraian aktivitas (long text) wajib diisi.',
            'content.min' => 'Uraian aktivitas minimal 10 karakter.',
        ]);

        $logbook->subject = $validated['subject'];
        $logbook->tanggal = $validated['tanggal'];
        $logbook->content = $validated['content'];

        // If logbook was in revision, reset to pending so dosen can re-verify
        if ($logbook->status === 'revision') {
            $logbook->status = 'pending';
        }

        $logbook->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil diperbarui dan siap ditinjau oleh Dosen Pembimbing.',
                'logbook' => $logbook->fresh(['student', 'dosen']),
            ]);
        }

        return redirect()->back()->with('success', 'Logbook berhasil diperbarui dan dikirim ulang ke Dosen Pembimbing!');
    }

    /**
     * Delete a logbook entry (by Mahasiswa).
     */
    public function destroy(Request $request, Logbook $logbook)
    {
        $user = Auth::user();

        // Ensure only the student owner or dosen can delete
        if ($logbook->user_id !== $user->id && $user->role !== 'dosen') {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus logbook ini.');
        }

        $logbook->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logbook berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * Update logbook status / approval by Dosen Pembimbing.
     */
    public function updateStatus(Request $request, Logbook $logbook)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,pending,revision'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $now = now();
        $logbook->status = $validated['status'];
        if (array_key_exists('feedback', $validated)) {
            $logbook->feedback = $validated['feedback'];
        }
        $logbook->updated_at = $now;
        $logbook->save();

        $message = match ($logbook->status) {
            'approved' => 'Logbook berhasil disetujui & diverifikasi dengan stempel digital pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB!',
            'revision' => 'Status logbook berhasil diubah menjadi Perlu Revisi dengan catatan arahan bimbingan.',
            default => 'Status logbook berhasil diperbarui pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB.',
        };

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'logbook' => $logbook->fresh(['student', 'dosen']),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
