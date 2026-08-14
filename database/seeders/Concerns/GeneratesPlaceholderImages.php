<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Storage;

trait GeneratesPlaceholderImages
{
    /**
     * Generates a solid-color PNG with a text label entirely offline (no
     * external HTTP calls), stores it on the "public" disk, and returns
     * the relative path expected by the views (url('storage/' . $path)).
     */
    protected function generatePlaceholderImage(string $directory, string $filename, string $label): string
    {
        $width = 400;
        $height = 300;

        $image = imagecreatetruecolor($width, $height);
        [$r, $g, $b] = $this->colorFromLabel($label);
        $background = imagecolorallocate($image, $r, $g, $b);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, $width, $height, $background);

        $font = 5; // built-in GD font, no .ttf file required
        $lines = explode("\n", wordwrap($label, 20, "\n", true));
        $lineHeight = imagefontheight($font) + 4;
        $startY = (int) (($height - count($lines) * $lineHeight) / 2);

        foreach ($lines as $i => $line) {
            $textWidth = imagefontwidth($font) * strlen($line);
            $x = (int) (($width - $textWidth) / 2);
            imagestring($image, $font, $x, $startY + $i * $lineHeight, $line, $textColor);
        }

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = "{$directory}/{$filename}.png";
        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    private function colorFromLabel(string $label): array
    {
        $hash = crc32($label);

        return [
            60 + ($hash & 0xFF) % 160,
            60 + (($hash >> 8) & 0xFF) % 160,
            60 + (($hash >> 16) & 0xFF) % 160,
        ];
    }
}
