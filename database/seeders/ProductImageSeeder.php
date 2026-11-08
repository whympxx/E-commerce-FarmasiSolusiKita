<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Membuat gambar ilustrasi kemasan (SVG) untuk tiap produk,
 * berisi nama produk dan bentuk kemasan yang sesuai.
 */
class ProductImageSeeder extends Seeder
{
    /** Bentuk kemasan per slug produk. Produk lain memakai bentuk dari unit-nya. */
    private const PACKAGING = [
        'paracetamol-500mg' => 'box',
        'obh-combi-batuk-berdahak' => 'syrup',
        'antimo-tablet' => 'box',
        'amoxicillin-500mg' => 'box',
        'vitamin-c-1000mg' => 'tube',
        'multivitamin-dewasa' => 'pillbottle',
        'betadine-antiseptik' => 'antiseptic',
        'tempra-sirup-anak' => 'syrup',
        'termometer-digital' => 'thermometer',
        'tolak-angin-cair' => 'sachet',
        'protein-whey-vanilla' => 'tub',
    ];

    private const UNIT_PACKAGING = [
        'strip' => 'box',
        'botol' => 'syrup',
        'tube' => 'tube',
        'sachet' => 'sachet',
        'tub' => 'tub',
        'pcs' => 'box',
    ];

    /** Warna utama per produk: [aksen, latar terang]. */
    private const COLORS = [
        'paracetamol-500mg' => ['#2563eb', '#dbeafe'],
        'obh-combi-batuk-berdahak' => ['#16a34a', '#dcfce7'],
        'antimo-tablet' => ['#0891b2', '#cffafe'],
        'amoxicillin-500mg' => ['#dc2626', '#fee2e2'],
        'vitamin-c-1000mg' => ['#ea580c', '#ffedd5'],
        'multivitamin-dewasa' => ['#7c3aed', '#ede9fe'],
        'betadine-antiseptik' => ['#b45309', '#fef3c7'],
        'tempra-sirup-anak' => ['#e11d48', '#ffe4e6'],
        'termometer-digital' => ['#0284c7', '#e0f2fe'],
        'tolak-angin-cair' => ['#ca8a04', '#fef9c3'],
        'protein-whey-vanilla' => ['#475569', '#f1f5f9'],
    ];

    public function run(): void
    {
        foreach (Product::with('category')->get() as $product) {
            $path = 'products/' . $product->slug . '.svg';
            Storage::disk('public')->put($path, $this->render($product));

            $product->images = [$path];
            $product->save();
        }
    }

    public function render(Product $product): string
    {
        $type = self::PACKAGING[$product->slug]
            ?? self::UNIT_PACKAGING[strtolower((string) $product->unit)]
            ?? 'box';
        [$accent, $light] = self::COLORS[$product->slug] ?? ['#0d9488', '#ccfbf1'];

        [$shape, $label] = $this->{'shape' . ucfirst($type)}($accent);

        $categorySlug = $product->category->slug ?? '';
        $badge = match (true) {
            str_contains($categorySlug, 'keras') || $product->prescription_required => ['#dc2626', 'K'],
            str_contains($categorySlug, 'bebas') => ['#16a34a', ''],
            default => null,
        };

        $labelSvg = $this->label($product, $label, $accent, $badge);
        $name = $this->e($product->name);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600" width="600" height="600" role="img" aria-label="{$name}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$light}"/>
      <stop offset="1" stop-color="#ffffff"/>
    </linearGradient>
    <linearGradient id="shine" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0" stop-color="#ffffff" stop-opacity="0"/>
      <stop offset="0.5" stop-color="#ffffff" stop-opacity="0.35"/>
      <stop offset="1" stop-color="#ffffff" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <rect width="600" height="600" fill="url(#bg)"/>
  <circle cx="500" cy="100" r="120" fill="{$accent}" opacity="0.07"/>
  <circle cx="80" cy="520" r="90" fill="{$accent}" opacity="0.06"/>
  <ellipse cx="300" cy="520" rx="175" ry="18" fill="#0f172a" opacity="0.12"/>
{$shape}
{$labelSvg}
</svg>
SVG;
    }

    // ---- Bentuk kemasan. Tiap fungsi mengembalikan [svg, area label x,y,w,h,warnaLatar,warnaTeks]. ----

    private function shapeBox(string $accent): array
    {
        $svg = <<<SVG
  <polygon points="170,190 210,160 440,160 400,190" fill="{$accent}" opacity="0.75"/>
  <polygon points="400,190 440,160 440,470 400,500" fill="{$accent}" opacity="0.55"/>
  <rect x="170" y="190" width="230" height="310" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
  <rect x="170" y="190" width="230" height="55" fill="{$accent}"/>
  <rect x="170" y="465" width="230" height="35" fill="{$accent}"/>
  <g transform="rotate(-10 420 440)">
    <rect x="335" y="390" width="180" height="105" rx="10" fill="#e2e8f0" stroke="#94a3b8" stroke-width="2"/>
    <g fill="#ffffff" stroke="#cbd5e1" stroke-width="2">
      <ellipse cx="370" cy="420" rx="20" ry="13"/><ellipse cx="425" cy="420" rx="20" ry="13"/><ellipse cx="480" cy="420" rx="20" ry="13"/>
      <ellipse cx="370" cy="465" rx="20" ry="13"/><ellipse cx="425" cy="465" rx="20" ry="13"/><ellipse cx="480" cy="465" rx="20" ry="13"/>
    </g>
  </g>
SVG;

        return [$svg, [182, 255, 205, 150, null, '#0f172a']];
    }

    private function shapeSyrup(string $accent): array
    {
        $svg = <<<SVG
  <rect x="255" y="115" width="90" height="55" rx="8" fill="{$accent}"/>
  <rect x="255" y="125" width="90" height="6" fill="#ffffff" opacity="0.3"/>
  <rect x="268" y="168" width="64" height="30" fill="#92400e"/>
  <path d="M268 196 Q200 205 200 250 L200 480 Q200 505 225 505 L375 505 Q400 505 400 480 L400 250 Q400 205 332 196 Z" fill="#b45309"/>
  <rect x="215" y="230" width="22" height="250" rx="11" fill="url(#shine)"/>
  <rect x="205" y="270" width="190" height="175" rx="6" fill="#ffffff"/>
  <rect x="205" y="270" width="190" height="14" fill="{$accent}"/>
  <rect x="205" y="431" width="190" height="14" fill="{$accent}"/>
SVG;

        return [$svg, [212, 290, 176, 135, null, '#0f172a']];
    }

    private function shapePillbottle(string $accent): array
    {
        $ridges = '';
        for ($x = 228; $x <= 372; $x += 16) {
            $ridges .= "<rect x=\"{$x}\" y=\"150\" width=\"6\" height=\"58\" fill=\"#ffffff\" opacity=\"0.25\"/>";
        }

        $svg = <<<SVG
  <rect x="215" y="145" width="170" height="68" rx="10" fill="{$accent}"/>
  {$ridges}
  <rect x="195" y="205" width="210" height="300" rx="34" fill="#ffffff" stroke="#e2e8f0" stroke-width="3"/>
  <rect x="195" y="262" width="210" height="185" fill="{$accent}"/>
  <rect x="210" y="230" width="20" height="260" rx="10" fill="url(#shine)"/>
SVG;

        return [$svg, [205, 272, 190, 165, null, '#ffffff']];
    }

    private function shapeAntiseptic(string $accent): array
    {
        $svg = <<<SVG
  <polygon points="288,95 312,95 318,160 282,160" fill="#78350f"/>
  <rect x="258" y="152" width="84" height="55" rx="8" fill="#92400e"/>
  <path d="M258 205 Q215 212 215 250 L215 480 Q215 505 240 505 L360 505 Q385 505 385 480 L385 250 Q385 212 342 205 Z" fill="#7c2d12"/>
  <rect x="228" y="235" width="20" height="245" rx="10" fill="url(#shine)"/>
  <rect x="222" y="275" width="156" height="170" rx="6" fill="#ffffff"/>
  <rect x="222" y="275" width="156" height="12" fill="{$accent}"/>
SVG;

        return [$svg, [228, 292, 144, 145, null, '#0f172a']];
    }

    private function shapeTube(string $accent): array
    {
        $svg = <<<SVG
  <rect x="235" y="110" width="130" height="62" rx="12" fill="{$accent}"/>
  <rect x="220" y="165" width="160" height="340" rx="18" fill="#ffffff" stroke="#e2e8f0" stroke-width="3"/>
  <rect x="220" y="195" width="160" height="10" fill="{$accent}"/>
  <rect x="220" y="465" width="160" height="10" fill="{$accent}"/>
  <rect x="232" y="185" width="16" height="300" rx="8" fill="url(#shine)"/>
  <circle cx="440" cy="455" r="42" fill="{$accent}" opacity="0.85"/>
  <circle cx="440" cy="455" r="30" fill="none" stroke="#ffffff" stroke-width="3" opacity="0.6"/>
  <g fill="{$accent}" opacity="0.35">
    <circle cx="430" cy="380" r="9"/><circle cx="460" cy="350" r="6"/><circle cx="445" cy="315" r="11"/><circle cx="470" cy="285" r="5"/>
  </g>
SVG;

        return [$svg, [226, 220, 148, 235, null, '#0f172a']];
    }

    private function shapeThermometer(string $accent): array
    {
        $svg = <<<SVG
  <g transform="rotate(-25 300 280)">
    <rect x="95" y="235" width="345" height="90" rx="45" fill="#ffffff" stroke="#cbd5e1" stroke-width="3"/>
    <rect x="95" y="235" width="120" height="90" rx="45" fill="{$accent}"/>
    <rect x="430" y="265" width="115" height="30" rx="15" fill="#cbd5e1"/>
    <circle cx="545" cy="280" r="17" fill="#94a3b8"/>
    <rect x="232" y="252" width="140" height="56" rx="8" fill="#bbf7d0" stroke="#86efac" stroke-width="2"/>
    <text x="302" y="292" text-anchor="middle" font-family="Consolas, 'Courier New', monospace" font-size="30" font-weight="700" fill="#14532d">36.5°C</text>
    <circle cx="395" cy="280" r="14" fill="{$accent}"/>
  </g>
  <rect x="130" y="390" width="340" height="105" rx="16" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
SVG;

        return [$svg, [145, 398, 310, 90, null, '#0f172a']];
    }

    private function shapeSachet(string $accent): array
    {
        $top = 'M200 140';
        for ($x = 200; $x < 400; $x += 20) {
            $top .= ' L' . ($x + 10) . ' 128 L' . ($x + 20) . ' 140';
        }
        $bottom = '';
        for ($x = 400; $x > 200; $x -= 20) {
            $bottom .= ' L' . ($x - 10) . ' 507 L' . ($x - 20) . ' 495';
        }

        $svg = <<<SVG
  <path d="{$top} L400 495{$bottom} Z" fill="{$accent}"/>
  <rect x="200" y="150" width="200" height="14" fill="#ffffff" opacity="0.25"/>
  <rect x="200" y="470" width="200" height="14" fill="#ffffff" opacity="0.25"/>
  <rect x="215" y="225" width="170" height="175" rx="10" fill="#ffffff"/>
  <path d="M300 445 C270 425 265 395 300 375 C335 395 330 425 300 445 Z" fill="#15803d" transform="translate(-55 0)"/>
  <path d="M300 445 C270 425 265 395 300 375 C335 395 330 425 300 445 Z" fill="#16a34a" transform="translate(55 0) rotate(20 300 410)"/>
SVG;

        return [$svg, [222, 235, 156, 155, null, '#0f172a']];
    }

    private function shapeTub(string $accent): array
    {
        $svg = <<<SVG
  <rect x="165" y="160" width="270" height="68" rx="14" fill="#1e293b"/>
  <rect x="165" y="175" width="270" height="8" fill="#ffffff" opacity="0.15"/>
  <rect x="175" y="222" width="250" height="285" rx="26" fill="{$accent}"/>
  <rect x="175" y="275" width="250" height="170" fill="#fef3c7"/>
  <rect x="190" y="240" width="20" height="250" rx="10" fill="url(#shine)"/>
SVG;

        return [$svg, [190, 285, 220, 150, null, '#0f172a']];
    }

    // ---- Label teks ----

    private function label(Product $product, array $area, string $accent, ?array $badge): string
    {
        [$x, $y, $w, $h, , $textColor] = $area;
        $cx = $x + $w / 2;
        $out = '';

        // Tanda golongan obat (hijau = obat bebas, merah K = obat keras)
        if ($badge) {
            [$color, $letter] = $badge;
            $bx = $x + $w - 14;
            $by = $y + 14;
            $out .= "  <circle cx=\"{$bx}\" cy=\"{$by}\" r=\"13\" fill=\"{$color}\" stroke=\"#0f172a\" stroke-width=\"2\"/>\n";
            if ($letter !== '') {
                $out .= "  <text x=\"{$bx}\" y=\"" . ($by + 6) . "\" text-anchor=\"middle\" font-family=\"Arial, sans-serif\" font-size=\"16\" font-weight=\"700\" fill=\"#ffffff\">{$letter}</text>\n";
            }
        }

        $brand = $product->brand ? mb_strtoupper($product->brand) : null;
        $brandColor = $textColor === '#ffffff' ? '#ffffff' : $accent;
        $brandSize = 14;

        // Pilih ukuran huruf terbesar yang muat; utamakan maksimal 2 baris, baru 3 baris
        $nameH = $h - ($brand ? 28 : 0);
        foreach ([2, 3] as $maxLines) {
            foreach ([40, 36, 32, 28, 25, 22, 20, 18] as $size) {
                [$lines, $fits] = $this->wrap($product->name, $w - 10, $size);
                if ($fits && count($lines) * $size * 1.15 <= $nameH && count($lines) <= $maxLines) {
                    break 2;
                }
            }
        }
        $lines = array_slice($lines, 0, 3);

        $blockH = count($lines) * $size * 1.15 + ($brand ? 24 : 0);
        $ty = $y + ($h - $blockH) / 2;

        if ($brand) {
            $ty += $brandSize;
            $out .= "  <text x=\"{$cx}\" y=\"{$ty}\" text-anchor=\"middle\" font-family=\"'Segoe UI', Arial, sans-serif\" font-size=\"{$brandSize}\" font-weight=\"600\" letter-spacing=\"2\" fill=\"{$brandColor}\" opacity=\"0.85\">" . $this->e($brand) . "</text>\n";
            $ty += 10;
        }

        foreach ($lines as $line) {
            $ty += $size;
            $out .= "  <text x=\"{$cx}\" y=\"{$ty}\" text-anchor=\"middle\" font-family=\"'Segoe UI', Arial, sans-serif\" font-size=\"{$size}\" font-weight=\"800\" fill=\"{$textColor}\">" . $this->e($line) . "</text>\n";
            $ty += $size * 0.15;
        }

        return $out;
    }

    /**
     * Pecah teks per kata agar tiap baris muat dalam lebar tertentu.
     *
     * @return array{0: string[], 1: bool} baris-baris teks, dan apakah semuanya muat
     */
    private function wrap(string $text, float $width, int $size): array
    {
        $maxChars = max(1, (int) floor($width / ($size * 0.56)));
        $lines = [];
        $current = '';

        foreach (preg_split('/\s+/', trim($text)) as $word) {
            $try = $current === '' ? $word : $current . ' ' . $word;
            if (mb_strlen($try) <= $maxChars || $current === '') {
                $current = $try;
            } else {
                $lines[] = $current;
                $current = $word;
            }
        }
        if ($current !== '') {
            $lines[] = $current;
        }

        // Satu kata yang lebih panjang dari lebar baris berarti tidak muat
        $fits = max(array_map('mb_strlen', $lines ?: [''])) <= $maxChars;

        return [$lines, $fits];
    }

    private function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
