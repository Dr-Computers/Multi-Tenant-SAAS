<?php

use App\Models\Currency;
use Illuminate\Support\Facades\Storage;
use App\Models\MediaFile;
use Spatie\Image\Image;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Unit;
use Spatie\Image\Enums\Fit;

if (!function_exists('uploadFile')) {
    /**
     * Handle file upload with optional watermark using Spatie Image package.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $destinationPath
     * @param  string  $disk
     * @param  bool    $watermark
     * @return string|null
     */
    function uploadFile($file, $destinationPath, $disk = 'public', $watermark = false)
    {
        if ($file && $file->isValid()) {
            // Generate a unique filename
            $filename = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Define storage paths
            $storagePath = storage_path('app/images/' . $destinationPath);
            $publicPath = public_path('images/' . $destinationPath);

            // Create directory if it doesn't exist
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Process the image
            $image = Image::load($file->getRealPath());

            // Apply watermark if enabled
            if ($watermark) {
                $logoPath = public_path('themes/images/Water-Mark.png'); // Make sure the path is correct
                // Make sure the watermark image exists
                if (file_exists($logoPath)) {
                    // Apply watermark at the center
                    $image->watermark(
                        $logoPath,
                        AlignPosition::Center,
                        width: 50,
                        widthUnit: Unit::Percent,
                        height: 50,
                        heightUnit: Unit::Percent
                    );
                }
                // Save the image with watermark
                $image->save($publicPath . '/' . $filename, $disk);
            } else {
                $path = $file->move($publicPath, $filename, $disk);
                // Save the image normally without watermark
                // $file->move($publicPath, $filename); // This saves the file directly in the public directory
            }

            // Return the relative path of the uploaded file (without 'images/')
            return  $destinationPath . '/' . $filename;
        }

        return null;
    }
}

if (!function_exists('uploadFiletoMedia')) {
    /**
     * Handle file upload to media with optional watermark using Spatie Image package.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $destinationPath
     * @param  string  $disk
     * @param  bool    $watermark
     * @return array|null
     */
    function uploadFiletoMedia($file, $destinationPath, $disk = 'public', $watermark = false)
    {
        if ($file && $file->isValid()) {
            // Generate a unique filename
            $filename = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Define storage path
            $storagePath = storage_path('app/images/' . $destinationPath);
            $publicPath = public_path('images/' . $destinationPath);
            // Create directory if it doesn't exist
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Process the image
            $image = Image::load($file->getRealPath());

            // Apply watermark if enabled
            if ($watermark) {
                $logoPath = public_path('themes/images/Water-Mark.png'); // Make sure the path is correct
                // Make sure the watermark image exists
                if (file_exists($logoPath)) {
                    // Apply watermark at the center
                    $image->watermark(
                        $logoPath,
                        AlignPosition::Center,
                        width: 50,
                        widthUnit: Unit::Percent,
                        height: 50,
                        heightUnit: Unit::Percent
                    );
                }
                // Save the image to the public directory (watermarked version)
                $image->save($publicPath . '/' . $filename, $disk);
            } else {
                $path = $file->storeAs('images/' . $destinationPath, $filename, $disk);
            }

            // Prepare data for insertion
            $mediaData = [
                'user_id'    => auth()->id() ?? 0, // Optional: Associate with logged-in user
                'name'       => $file->getClientOriginalName(),
                'alt'        => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), // Optional alt text
                'folder_id'  => 0, // Set the appropriate folder ID if needed
                'mime_type'  => $file->getClientMimeType(),
                'size'       => $file->getSize(),
                'url'        => $destinationPath . '/' . $filename,
                'options'    => null, // Add any additional options or metadata
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert data into the database
            $mediaId = MediaFile::insertGetId($mediaData);

            // Return file information or media ID
            return ['media_id' => $mediaId, 'file_path' => $destinationPath . '/' . $filename];
        }

        return null;
    }
}


// if (!function_exists('uploadFile')) {
//     /**
//      * Handle file upload.
//      *
//      * @param  \Illuminate\Http\UploadedFile  $file
//      * @param  string  $destinationPath
//      * @param  string  $disk
//      * @return string|null
//      */
//     function uploadFile($file, $destinationPath, $disk = 'public',$watermark = false)
//     {
//         if ($file && $file->isValid()) {
//             // Generate a unique filename
//             $filename = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();

//             // Store the file on the specified disk
//             $path = $file->storeAs('images/' . $destinationPath, $filename, $disk);

//             // Return the path of the uploaded file
//             return str_replace('images/', '', $path);
//         }

//         return null;
//     }
// }
// if (!function_exists('uploadFiletoMedia')) {

//     function uploadFiletoMedia($file, $destinationPath, $disk = 'public',$watermark = false)
//     {
//         if ($file && $file->isValid()) {
//             // Generate a unique filename
//             $filename = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();

//             // Store the file on the specified disk
//             $path = $file->storeAs('images/' . $destinationPath, $filename, $disk);

//             // Prepare data for insertion
//             $mediaData = [
//                 'user_id'    => auth()->id() ?? 0, // Optional: Associate with logged-in user
//                 'name'       => $file->getClientOriginalName(),
//                 'alt'        => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), // Optional alt text
//                 'folder_id'  => 0, // Set the appropriate folder ID if needed
//                 'mime_type'  => $file->getClientMimeType(),
//                 'size'       => $file->getSize(),
//                 'url'        => str_replace('images/', '', $path),
//                 'options'    => null, // Add any additional options or metadata
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ];

//             // Insert data into the database
//             $mediaId = MediaFile::insertGetId($mediaData);

//             // Return file information or media ID
//             return ['media_id' => $mediaId, 'file_path' => str_replace('images/', '', $path)];
//         }

//         return null;
//     }
// }


if (!function_exists('deleteFilefromMedia')) {

    function deleteFilefromMedia($id)
    {

        $media = MediaFile::where('id', $id)->first();

        if ($media) {
            if ($media->url && Storage::disk('public')->exists('images/' . $media->url)) {

                Storage::disk('public')->delete('images/' . $media->url);
            }
        }

        return null;
    }
}



if (!function_exists('deleteFile')) {
    /**
     * Delete a file from storage.
     *
     * @param  string  $filePath
     * @param  string  $disk
     * @return bool
     */
    function deleteFile($filePath, $disk = 'public')
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            // Delete the file
            return Storage::disk($disk)->delete($filePath);
        }

        return false;
    }
}

function stringify($content): string|null
{
    if (empty($content)) {
        return null;
    }

    if (is_string($content) || is_numeric($content) || is_bool($content)) {
        return $content;
    }

    if (is_array($content)) {
        return json_encode($content);
    }

    return null;
}


if (!function_exists('clean')) {
    function clean($dirty, $config = null)
    {
        return $dirty;
    }
}


if (!function_exists('shorten_price')) {
    function shorten_price($price)
    {
        if ($price >= 10000000) {
            return '₹' . indian_number_format($price / 10000000, 2) . ' Cr';
        } elseif ($price >= 100000) {
            return '₹' . indian_number_format($price / 100000, 2) . ' L';
        } elseif ($price >= 1000) {
            return '₹' . indian_number_format($price / 1000, 2) . ' K';
        } else {
            return '₹' . indian_number_format($price, 2);
        }
    }
}



if (! function_exists('format_price')) {
    function format_price(
        float|null|string $price,
        Currency|null|string $currency = null,
        bool $withoutCurrency = false,
        bool $useSymbol = true,
        bool $fullNumber = false
    ): string {
        if ($currency) {
            if (! $currency instanceof Currency) {
                $currency = Currency::query()->find($currency);
            }

            if (! $currency) {
                return human_price_text($price, $currency, fullNumber: $fullNumber);
            }

            if ($currency->getKey() != get_application_currency_id() && $currency->exchange_rate > 0) {
                $currentCurrency = get_application_currency();

                if ($currentCurrency->is_default) {
                    $price = $price / $currency->exchange_rate;
                } else {
                    $price = $price / $currency->exchange_rate * $currentCurrency->exchange_rate;
                }

                $currency = $currentCurrency;
            }
        } else {
            $currency = get_application_currency();

            if (! $currency) {
                return human_price_text($price, $currency);
            }

            if (! $currency->is_default && $currency->exchange_rate > 0) {
                $price = $price * $currency->exchange_rate;
            }
        }

        if ($withoutCurrency) {
            return (string)$price;
        }

        if ($useSymbol && $currency->is_prefix_symbol) {
            $space = setting('real_estate_add_space_between_price_and_currency', 0) == 1 ? ' ' : null;

            return $currency->symbol . $space . human_price_text($price, $currency, fullNumber: $fullNumber);
        }

        return human_price_text($price, $currency, ($useSymbol ? $currency->symbol : $currency->title), fullNumber: $fullNumber);
    }
}



if (!function_exists('dateTimeFormat')) {

    function dateTimeFormat($date)
    {
        $date = date('d M,Y',strtotime($date));
        $time = date('h:i a',strtotime($date));
        return  $date . ',' . $time ;
    }
}

function indian_number_format($number) {
    $decimal = ''; // To store decimal part if needed
    if (strpos($number, '.') !== false) {
        [$number, $decimal] = explode('.', $number); // Split integer and decimal parts
        $decimal = '.' . $decimal; // Reattach decimal point
    }

    // Convert the number to a string and reverse it
    $number = strrev($number);

    // Insert commas after the first 3 digits, then every 2 digits
    $formatted = preg_replace('/(\d{3})(?=\d)/', '$1,', $number);
    $formatted = preg_replace('/(\d{2})(?=(\d{2},)+\d)/', '$1,', $formatted);

    // Reverse the string back to normal
    $formatted = strrev($formatted);

    // Reattach decimal part if present
    return $formatted . $decimal;
}
