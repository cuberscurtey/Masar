<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    // عرض مدفوعات حجز العميل
    public function index($booking_id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($booking_id);

        if (!$booking) {
            return response()->json([
                'message' => 'الحجز غير موجود'
            ], 404);
        }

        $payments = Payment::where('booking_id', $booking_id)->get();

        return response()->json([
            'message' => 'تم جلب المدفوعات بنجاح',
            'data' => $payments
        ], 200);
    }

    // عرض دفعة محددة للعميل
    public function show($id)
    {
        $payment = Payment::whereHas('booking', function ($query) {
            $query->where('user_id', auth()->id());
        })->find($id);

        if (!$payment) {
            return response()->json([
                'message' => 'الدفعة غير موجودة'
            ], 404);
        }

        return response()->json([
            'message' => 'تم جلب بيانات الدفعة بنجاح',
            'data' => $payment
        ], 200);
    }

    // إضافة دفعة جديدة من العميل
    public function store(Request $request, $booking_id)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->find($booking_id);

        if (!$booking) {
            return response()->json([
                'message' => 'الحجز غير موجود'
            ], 404);
        }

        if (
            $booking->status !== 'pending' ||
            !$booking->expires_at ||
            $booking->expires_at->isPast()
        ) {
            return response()->json([
                'message' => 'لا يمكن إضافة دفعة لهذا الحجز'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,bank_transfer,wallet',
            'reference' => 'nullable|string|max:150',
            'receipt_path' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $payment = Payment::create([
            'booking_id' => $booking_id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'status' => 'pending',
            'reference' => $validated['reference'] ?? null,
            'receipt_path' => $validated['receipt_path'] ?? null,
        ]);

        return response()->json([
            'message' => 'تم تسجيل الدفعة وهي قيد المراجعة',
            'data' => $payment
        ], 201);
    }

    // عرض الدفعات المعلقة للمحاسب
    public function pending()
    {
        $payments = Payment::with('booking')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'تم جلب الدفعات المعلقة بنجاح',
            'data' => $payments
        ], 200);
    }

    // اعتماد أو رفض دفعة بواسطة المحاسب
    public function review(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'message' => 'الدفعة غير موجودة'
            ], 404);
        }

        if ($payment->status !== 'pending') {
            return response()->json([
                'message' => 'تمت مراجعة هذه الدفعة مسبقاً'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'حالة المراجعة غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $payment->update([
            'status' => $validator->validated()['status'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'تمت مراجعة الدفعة بنجاح',
            'data' => $payment->fresh()
        ], 200);
    }
}
