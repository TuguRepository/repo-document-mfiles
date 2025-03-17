<?php

/**
 * Approve the specified request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function approve(Request $request, $id)
{
    try {
        // Find the request
        $dataRequest = DataRequest::findOrFail($id);

        // Update request status
        $dataRequest->status = 'approved';
        $dataRequest->approved_at = now();
        $dataRequest->approved_by = auth()->id();
        $dataRequest->admin_note = $request->approval_note;

        // Set access expiration if limit_time is checked
        if ($request->limit_time) {
            $dataRequest->access_expires_at = now()->addHours(24);
        }

        $dataRequest->save();

        // Get the file that was requested
        $file = File::findOrFail($dataRequest->file_id);
        $filePath = storage_path('app/' . $file->file_path);

        // Send email notification if checked
        if ($request->send_email) {
            // Get the requester information
            $requester = User::findOrFail($dataRequest->user_id);

            // Prepare email data
            $emailData = [
                'user' => $requester,
                'request' => $dataRequest,
                'note' => $request->approval_note,
                'expiration' => $request->limit_time ? now()->addHours(24) : null
            ];

            // Send email with or without file attachment
            if ($request->send_file && file_exists($filePath)) {
                Mail::to($requester->email)
                    ->send(new RequestApproved($emailData, $filePath, $file->original_name));
            } else {
                Mail::to($requester->email)
                    ->send(new RequestApproved($emailData));
            }
        }

        // Log the activity
        activity()
            ->performedOn($dataRequest)
            ->causedBy(auth()->user())
            ->withProperties([
                'status' => 'approved',
                'note' => $request->approval_note,
                'email_sent' => $request->send_email,
                'file_attached' => $request->send_file && $request->send_email,
                'time_limited' => $request->limit_time
            ])
            ->log('approved request');

        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil disetujui.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}
