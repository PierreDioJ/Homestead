<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|integer|exists:listings,id', // Убедитесь, что таблица `listings` существует
            'reason' => 'required|string',
            'details' => 'nullable|string',
        ]);

        // Создать жалобу
        Report::create([
            'listing_id' => $request->listing_id,
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        // Вернуть успешный ответ
        return response()->json(['message' => 'Жалоба успешно отправлена!']);
    }
}

