<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Chỉ minify HTML
        if (
            $response->headers->has('Content-Type') &&
            str_contains($response->headers->get('Content-Type'), 'text/html')
        ) {
            $output = $response->getContent();

            // Không minify trong <pre>, <textarea>, <code>
            $ignore = ['pre', 'textarea', 'code'];
            $placeholders = [];

            foreach ($ignore as $tag) {
                preg_match_all("#<{$tag}.*?>.*?</{$tag}>#si", $output, $matches);

                foreach ($matches[0] as $i => $match) {
                    $placeholder = "@@MINIFY_BLOCK_{$tag}_{$i}@@";
                    $placeholders[$placeholder] = $match;
                    $output = str_replace($match, $placeholder, $output);
                }
            }

            // Minify HTML (loại bỏ khoảng trắng dư, xuống dòng, comment)
            $output = preg_replace([
                '/\>[^\S ]+/s',     // loại bỏ whitespace sau tag >
                '/[^\S ]+\</s',     // loại bỏ whitespace trước tag <
                '/<!--.*?-->/s',    // xóa comment HTML
                '/\s{2,}/'          // gom space
            ], [
                '>',
                '<',
                '',
                ' '
            ], $output);

            // Trả lại các block cần giữ nguyên
            foreach ($placeholders as $ph => $original) {
                $output = str_replace($ph, $original, $output);
            }

            $response->setContent($output);
        }

        return $response;
    }
}
