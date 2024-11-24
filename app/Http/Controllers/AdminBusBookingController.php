<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusBooking;

class AdminBusBookingController extends Controller
{
    public function index()
    {
        // Fetch all bus bookings with related data and group by user (passenger)
        $bookings = BusBooking::with(['bus', 'user'])
            ->get()
            ->groupBy('user_id');

        // Pass the grouped bookings to the admin view
        return view('admin.bus_bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Find the booking by ID
        $booking = BusBooking::findOrFail($id);

        // Validate the incoming status and remarks
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Update the booking status and remarks
        $booking->status = $request->status;
        $booking->remarks = $request->remarks;
        $booking->save();

        // Provide feedback to the admin
        return redirect()->route('admin.bookings')->with('success', 'Booking status updated successfully.');
    }
}

