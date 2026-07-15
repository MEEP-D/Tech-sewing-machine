<?php

namespace App\Filament\Support;

final class AdminFormValidation
{
    public static function text(int $max = 255): array
    {
        return ['nullable', 'string', "max:{$max}"];
    }

    public static function requiredText(int $max = 255): array
    {
        return ['required', 'string', "max:{$max}"];
    }

    public static function slug(): array
    {
        return ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'];
    }

    public static function internalOrAbsoluteUrl(int $max = 500): array
    {
        return ['nullable', 'string', "max:{$max}", 'regex:/^(\/.*|https?:\/\/\S+)$/i'];
    }

    public static function hexColor(): array
    {
        return ['nullable', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'];
    }

    public static function nonNegativeInteger(): array
    {
        return ['nullable', 'integer', 'min:0'];
    }

    public static function percentage(): array
    {
        return ['nullable', 'integer', 'min:0', 'max:100'];
    }

    public static function phone(int $max = 50): array
    {
        return ['nullable', 'string', "max:{$max}", 'regex:/^[0-9+\s().\/-]{9,50}$/'];
    }

    public static function messages(): array
    {
        return [
            'required' => 'Vui lòng nhập :attribute.',
            'string' => ':attribute phải là nội dung dạng văn bản.',
            'integer' => ':attribute phải là số nguyên.',
            'numeric' => ':attribute phải là số.',
            'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            'max' => ':attribute không được vượt quá :max ký tự hoặc giá trị tối đa cho phép.',
            'email' => 'Địa chỉ email không đúng định dạng.',
            'url' => ':attribute phải là liên kết hợp lệ, ví dụ https://example.com.',
            'unique' => ':attribute đã tồn tại trong hệ thống.',
            'exists' => ':attribute đã chọn không tồn tại hoặc không còn khả dụng.',
            'date' => ':attribute phải là ngày hợp lệ.',
            'after_or_equal' => ':attribute phải sau hoặc bằng :date.',
            'regex' => ':attribute chưa đúng định dạng yêu cầu.',
            'json' => ':attribute phải là JSON hợp lệ.',
        ];
    }

    public static function slugMessages(): array
    {
        return array_replace(self::messages(), [
            'regex' => 'Slug chỉ được dùng chữ thường không dấu, số và dấu gạch ngang. Ví dụ: may-may-cong-nghiep.',
        ]);
    }

    public static function urlMessages(): array
    {
        return array_replace(self::messages(), [
            'regex' => 'Liên kết phải là URL đầy đủ (https://...) hoặc đường dẫn nội bộ bắt đầu bằng "/".',
        ]);
    }

    public static function hexColorMessages(): array
    {
        return array_replace(self::messages(), [
            'regex' => 'Màu sắc phải là mã HEX hợp lệ, ví dụ #ffffff hoặc #0f172a.',
        ]);
    }

    public static function phoneMessages(): array
    {
        return array_replace(self::messages(), [
            'regex' => 'Số điện thoại phải có từ 9 đến 50 ký tự và chỉ gồm số, khoảng trắng, dấu +, dấu chấm, dấu ngoặc, dấu gạch ngang hoặc dấu /.'
        ]);
    }
}
