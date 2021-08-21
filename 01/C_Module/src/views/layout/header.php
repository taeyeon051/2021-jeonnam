<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/공통/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" href="/공통/bootstrap-5.0.1-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/sub.css">
    <title>대전 빵집</title>
</head>

<body>
    <div id="container">
        <!-- 헤더 영역 -->
        <header class="w-100 bg-white d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="/logo.png" alt="logo" title="logo" width="120">
            </div>
            <ul id="menu">
                <li><a href="/daejeon_bakery">대전 빵집</a></li>
                <li><a href="/stamp">스탬프</a></li>
                <li><a href="/sale_event">할인 이벤트</a></li>
                <li><a href="/mypage">마이페이지</a></li>
            </ul>
            <ul>
                <?php if (__SESSION) : ?>
                    <?php $user = $_SESSION['user']; ?>
                    <li class="user-info">&lt;<?= $user->name ?>&gt;<span>(<?= $user->type ?>)</span></li>
                    <li><a href="/logout">로그아웃</a></li>
                <?php else : ?>
                    <li><a href="/login">로그인</a></li>
                <?php endif; ?>
            </ul>
        </header>