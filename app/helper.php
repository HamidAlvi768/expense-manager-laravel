<?php

use App\Models\Notification;

use Carbon\Carbon;


use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Mail;

if (!function_exists('sendDynamicEmail')) {
    function sendDynamicEmail($recipientName, $recipientEmail, $messageBody)
    {
        // Validate email address format
        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address provided.");
        }

        Mail::to($recipientEmail)->send(new DynamicMail($recipientName, $messageBody));
    }
}

if (!function_exists('getDocNumber')) {
    function getDocNumber($id,$type='')
    {
        return $type . date('y') . date('m') . str_pad($id,  STR_PAD_LEFT);


    }
}

if (!function_exists('formatAmount')) {
    /**
     * Format a numeric amount with commas and no decimal places.
     *
     * @param float|int $amount The numeric amount to format.
     * @return string The formatted amount as a string.
     */
    function formatAmount($amount)
    {
        // Ensure the input is numeric before formatting
        if (!is_numeric($amount)) {
            return $amount; // Return original value if not numeric
        }

        return number_format((float) $amount, 0, '.', ',');
    }
}

// helpers.php

if (!function_exists('getCategoryColors')) {
    function getCategoryColors($categories)
    {
        $colors = [];
        $hashColors = [
            '#FFB6C1', // Light Pink
            '#FFD700', // Gold
            '#ADFF2F', // Green Yellow
            '#87CEFA', // Light Sky Blue
            '#F0E68C', // Khaki
            '#F5F5DC', // Beige
            '#FF69B4', // Hot Pink
            '#FFBFFF', // Light Lavender
            '#98FB98', // Pale Green
            '#FFDEAD', // Navajo White
            '#E6E6FA', // Lavender
            '#FFCCCB', // Light Red
            '#FFB5C5', // Light Coral
            '#B0E0E6', // Powder Blue
            '#FFE4E1', // Misty Rose
            '#D8BFD8', // Thistle
            '#FFFACD', // Lemon Chiffon
            '#F0FFF0', // Honeydew
            '#D3D3D3', // Light Gray
            '#D0E0E3', // Light Steel Blue
            '#F5F5F5', // White Smoke
        ];
        $i = 0;

        foreach ($categories as $category) {
            $colors[$category->id] = $hashColors[$i % count($hashColors)];
            $i++;
        }

        return $colors;
    }
}



if (!function_exists('displayIndexValue')) {
    function displayIndexValue($index,$array)
    {
        $index = trim($index);
        if(is_numeric($index)) {
            if(array_key_exists($index,$array)) {
                return $array[$index];
            } else {
                return $index;
            }
        } else if(!empty($index)) {
            return $index;
        }
    }
}




if (!function_exists('calculateAge')) {
    function calculateAge($date_of_birth)
    {
        $dob = Carbon::parse($date_of_birth);
        $today = Carbon::now();

        $years = $today->diffInYears($dob); // Use $dob instead of $date_of_birth

        return [
            'years' => $years,
        ];
    }
}


if (!function_exists('sendNotification')) {
    function sendNotification($Id, $url, $msg, $userId = null)
    {
        $notification = new Notification([
            'notification_from' => auth()->id(),
            'notification_to' => $userId ?? 1,
            'text' => $msg,
            'url' => route($url, $Id),
        ]);
        $notification->save();
    }
}
