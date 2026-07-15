<?php

namespace App\Filament\Support;

class VietnameseAction
{
    public static function create(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Thêm mới')
            ->modalSubmitActionLabel('Thêm mới')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã tạo {$subject} thành công.");
    }

    public static function edit(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Chỉnh sửa')
            ->modalSubmitActionLabel('Lưu thay đổi')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã lưu thay đổi {$subject} thành công.");
    }

    public static function delete(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Xóa dữ liệu')
            ->requiresConfirmation()
            ->modalHeading("Xác nhận xóa {$subject}")
            ->modalDescription("Bạn có chắc chắn muốn xóa {$subject} này không? Hành động này có thể không hoàn tác được.")
            ->modalSubmitActionLabel('Xóa dữ liệu')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã xóa {$subject} thành công.");
    }

    public static function deleteBulk(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Xóa dữ liệu đã chọn')
            ->requiresConfirmation()
            ->modalHeading("Xác nhận xóa {$subject} đã chọn")
            ->modalDescription("Bạn có chắc chắn muốn xóa các {$subject} đã chọn không? Hành động này có thể không hoàn tác được.")
            ->modalSubmitActionLabel('Xóa dữ liệu')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã xóa các {$subject} đã chọn thành công.");
    }

    public static function forceDelete(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Xóa vĩnh viễn')
            ->requiresConfirmation()
            ->modalHeading("Xác nhận xóa vĩnh viễn {$subject}")
            ->modalDescription("{$subject} sẽ bị xóa vĩnh viễn và không thể khôi phục. Bạn có chắc chắn muốn tiếp tục không?")
            ->modalSubmitActionLabel('Xóa vĩnh viễn')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã xóa vĩnh viễn {$subject} thành công.");
    }

    public static function forceDeleteBulk(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Xóa vĩnh viễn dữ liệu đã chọn')
            ->requiresConfirmation()
            ->modalHeading("Xác nhận xóa vĩnh viễn {$subject} đã chọn")
            ->modalDescription("Các {$subject} đã chọn sẽ bị xóa vĩnh viễn và không thể khôi phục. Bạn có chắc chắn muốn tiếp tục không?")
            ->modalSubmitActionLabel('Xóa vĩnh viễn')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã xóa vĩnh viễn các {$subject} đã chọn thành công.");
    }

    public static function restore(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Khôi phục dữ liệu')
            ->requiresConfirmation()
            ->modalHeading("Xác nhận khôi phục {$subject}")
            ->modalDescription("Bạn có muốn khôi phục {$subject} này không?")
            ->modalSubmitActionLabel('Khôi phục dữ liệu')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã khôi phục {$subject} thành công.");
    }

    public static function restoreBulk(mixed $action, string $subject): mixed
    {
        return $action
            ->label('Khôi phục dữ liệu đã chọn')
            ->requiresConfirmation()
            ->modalHeading("Xác nhận khôi phục {$subject} đã chọn")
            ->modalDescription("Bạn có muốn khôi phục các {$subject} đã chọn không?")
            ->modalSubmitActionLabel('Khôi phục dữ liệu')
            ->modalCancelActionLabel('Hủy bỏ')
            ->successNotificationTitle("Đã khôi phục các {$subject} đã chọn thành công.");
    }
}
