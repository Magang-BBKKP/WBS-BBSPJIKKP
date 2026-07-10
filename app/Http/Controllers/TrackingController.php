<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Bukti;
use App\Models\LaporanMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrackingController extends Controller
{
    /**
     * Show the access code input form.
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Handle the access code submission.
     */
    public function search(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $code = trim($request->access_code);
        $laporan = Laporan::where('tracking_token', $code)
            ->orWhere('nomor_registrasi', $code)
            ->first();

        if (!$laporan) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan dengan kode akses tersebut.');
        }

        return redirect()->route('track.show', ['token' => $laporan->tracking_token]);
    }

    /**
     * Show the tracking details page.
     */
    public function show($token)
    {
        $laporan = Laporan::with(['timelines', 'messages.user', 'buktis'])->where('tracking_token', $token)->firstOrFail();
        return view('tracking.show', compact('laporan'));
    }

    /**
     * Upload additional evidence.
     */
    public function storeEvidence(Request $request, $token)
    {
        $laporan = Laporan::where('tracking_token', $token)->firstOrFail();

        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('buktis', $fileName, 'public');

        Bukti::create([
            'laporan_id' => $laporan->id,
            'nama_asli'  => $file->getClientOriginalName(),
            'nama_file'  => $fileName,
            'path_file'  => $path,
            'mime_type'  => $file->getMimeType(),
            'ukuran'     => $file->getSize(),
        ]);

        return redirect()->route('track.show', ['token' => $token])->with('success', 'Bukti berhasil ditambahkan.');
    }

    /**
     * Fetch messages (Ajax).
     */
    public function fetchMessages($token)
    {
        $laporan = Laporan::with('messages.user')->where('tracking_token', $token)->firstOrFail();
        return response()->json([
            'messages' => $laporan->messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'content' => $msg->content,
                    'time' => $msg->created_at->format('h:i A'),
                ];
            })
        ]);
    }

    /**
     * Store a new message in the secure channel.
     */
    public function storeMessage(Request $request, $token)
    {
        $laporan = Laporan::where('tracking_token', $token)->firstOrFail();

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $msg = LaporanMessage::create([
            'laporan_id' => $laporan->id,
            'sender_type' => 'pelapor',
            'content' => $request->message,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'content' => $msg->content,
                    'time' => $msg->created_at->format('h:i A'),
                ]
            ]);
        }

        return redirect()->route('track.show', ['token' => $token])->with('success', 'Pesan berhasil dikirim.');
    }
}
