<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 收集表单数据
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $jobObjective = isset($_POST['jobObjective']) ? $_POST['jobObjective'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // 处理上传的图片
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // 设置文件存储目录
        $uploadDir = './web技术/photo';  // 目标目录
        $fileTmpPath = $_FILES['photo']['tmp_name']; // 获取上传文件的临时路径
        $fileName = basename($_FILES['photo']['name']); // 获取文件原始名称
        $uploadFile = $uploadDir . '/' . $fileName; // 构建文件存储路径

        // 检查目录是否存在，如果不存在则创建
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true); // 创建目录，并设置权限为 0777
        }

        // 移动上传的文件到目标目录
        if (move_uploaded_file($fileTmpPath, $uploadFile)) {
            $photoPath = $uploadFile; // 上传成功，保存路径
        } else {
            $photoPath = ''; // 上传失败，设置为空
        }
    } else {
        $photoPath = ''; // 如果没有上传图片，设置为空
    }

    // 验证输入
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $birthdate = filter_var($birthdate, FILTER_SANITIZE_STRING);
    $jobObjective = filter_var($jobObjective, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $address = filter_var($address, FILTER_SANITIZE_STRING);

    // 读取原始简历文件
    $resumeContent = file_get_contents('resume.html');

    // 替换简历内容中的特定字段
    $resumeContent = preg_replace('/姓名：[^<]*/', "姓名：$name", $resumeContent);
    $resumeContent = preg_replace('/出生日期：[^<]*/', "出生日期：$birthdate", $resumeContent);
    $resumeContent = preg_replace('/职业目标：[^<]*/', "职业目标：$jobObjective", $resumeContent);
    $resumeContent = preg_replace('/联系方式：[^<]*/', "联系方式：$email", $resumeContent);
    $resumeContent = preg_replace('/电话：[^<]*/', "电话：$phone", $resumeContent);
    $resumeContent = preg_replace('/地址：[^<]*/', "地址：$address", $resumeContent);

    // 如果有上传图片，替换简历中的图片路径
    if (!empty($photoPath)) {
        $resumeContent = preg_replace('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/', '<img src="' . $photoPath . '" />', $resumeContent);
    }

    // 将更新后的内容写回简历文件
    file_put_contents('resume.html', $resumeContent);

    // 反馈信息并重定向
    header('Location: resume.html?updated=1');
    exit();
} else {
    echo "无效的请求";
}
?>