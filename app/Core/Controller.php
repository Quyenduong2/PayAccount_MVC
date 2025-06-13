<?php
class Controller
{
    // Hiển thị view với header/footer chung
    protected function view($viewPath, $data = [])
    {
        // Giải nén biến từ mảng $data thành biến riêng lẻ
        extract($data);

        // Đường dẫn tuyệt đối tới view
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        $headerFile = __DIR__ . '/../Views/component/Header.php';
        $footerFile = __DIR__ . '/../Views/component/Footer.php';

        if (file_exists($headerFile)) require $headerFile;
        if (file_exists($viewFile)) require $viewFile;
        if (file_exists($footerFile)) require $footerFile;
    }
}