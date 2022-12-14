<?php

// セッション開始
session_start();

// 外部ファイルのインポート
require_once '../../../../class/Session_calc.php';
require_once '../../../../class/Validation_calc.php';
require_once '../../../../function/functions.php';
require_once '../../../../class/View_calc.php';
require_once '../../../../class/Like_calc.php';

// インスタンス化
$ses_calc = new Session();
$val_calc = new ValidationCheck();
$viw_calc = new View();
$lik_calc = new Like();

// ログインチェック
$student_login_data = $ses_calc->student_login_check();

// ユーザIDを抽出
foreach ($student_login_data as $row) {
    $user_id = $row['student_id'];
}

// ユーザ名を抽出
foreach ($student_login_data as $row) {
    $user_name = $row['name'];
}

// ユーザアイコンを抽出
foreach ($student_login_data as $row) {
    $user_icon = $row['icon'];
}

// ログイン情報がない場合リダイレクト
if (!$student_login_data) {
    $uri = '../../../Exception/400_request.php';
    header('Location: ' . $uri);
}

// GETで現在のページ数を取得する（未入力の場合は1を挿入）
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1;
}

// スタートのポジションを計算する
if ($page > 1) {
    $start = ($page * 10) - 10;
} else {
    $start = 0;
}

// ES体験記投稿データを取得
$es_experience_data = $viw_calc->es_experience_data($start);

// ES体験記の投稿数を取得
$page_num = $viw_calc->es_experience_data_val();

// ページネーションの数を取得する
$pagination = ceil($page_num / 10);

// POSTリクエストがlikeの場合投稿にいいねする
if (isset($_POST['like'])) {

    // csrfトークンの存在確認
    $csrf_check = $ses_calc->csrf_match_check($_POST['csrf_token']);

    // csrfトークンの正誤判定
    if (!$csrf_check) {
        $uri = '../../../Exception/400_request.php';
        header('Location:' . $uri);
    }

    // 投稿にいいねをする
    $lik_calc->es_experience_like($_POST['post_id'], $user_id);

    // csrf_token削除　二重送信対策
    $ses_calc->csrf_token_unset();
    $uri = './posts.php';
    header('Location: ' . $uri);
}

// POSTリクエストがlike_deleteの場合投稿にいいねする
if (isset($_POST['like_delete'])) {

    // csrfトークンの存在確認
    $csrf_check = $ses_calc->csrf_match_check($_POST['csrf_token']);

    // csrfトークンの正誤判定
    if (!$csrf_check) {
        $uri = '../../../Exception/400_request.php';
        header('Location:' . $uri);
    }

    // 投稿のいいねを解除する
    $lik_calc->es_experience_like_delete($_POST['post_id'], $user_id);

    // csrf_token削除　二重送信対策
    $ses_calc->csrf_token_unset();
    $uri = './posts.php';
    header('Location: ' . $uri);
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../../../../public/img/favicon.ico" type="image/x-icon">
    <title>ES体験記 /「Real intentioN」</title>
    <style>
        body {
            background-color: #EFF5F5;
        }

        header {
            background-color: #c2dbde;
        }

        footer {
            background-color: #497174;
        }

        .nav-link {
            font-weight: bold;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .login-btn {
            background-color: #EB6440;
            color: white;
        }

        .login-btn:hover {
            color: white;
            background-color: #eb6540c4;
        }
    </style>

    <script>
        function alertFunction1(value) {
            var submit = confirm("本当に削除しますか？");

            if (submit) {
                window.location.href = './delete/delete.php?post_id=' + value;
            } else {
                window.location.href = './posts.php';
            }
        }
    </script>
</head>

<body>
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light py-4">
            <div class="container">
                <a class="navbar-brand" href="">
                    <img src="../../../../public/img/logo.png" alt="" width="30" height="24" class="d-inline-block
                            align-text-top" style="object-fit: cover;"> Real intentioN
                </a>
            </div>
        </nav>
    </header>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-12">
                <?php if (is_array($es_experience_data) || is_object($es_experience_data)) : ?>
                    <?php foreach ($es_experience_data as $row) : ?>
                        <div class="intern-contents mb-5 px-4 py-4 bg-light">
                            <div class="row mt-2">
                                <div class="info-left col-lg-2 col-md-2 col-4">
                                    <div class="text-center">
                                        <div class="ratio ratio-1x1" style="background-color: #ffad60; border-radius: 5px;">
                                            <div class="fs-5 fw-bold d-flex align-items-center justify-content-center">
                                                ES
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-9 col-7">
                                    <p class="mt-2 fs-5 fw-bold">
                                        <?php h($row['company']) ?><span style="margin: 0 10px;">/</span><?php h($row['field']) ?>
                                    </p>
                                </div>

                                <div class="info-right col-lg-1 col-md-1 col-1">
                                    <div class="text-end">
                                        <div class="btn-group">
                                            <?php if ($user_id == $row['student_id']) : ?>
                                                <div class="btn-group dropstart" role="group">
                                                    <button type="button" class="py-2 btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-dark">
                                                        <li><button class="dropdown-item" value="<?php h($row['post_id']) ?>" onclick="alertFunction1(this.value)">削除</button></li>

                                                        <li><a class="dropdown-item" href="./update/update_form.php?post_id=<?php h($row['post_id']) ?>">編集</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-1">
                                        <div class="text-end">
                                            <span style="color: blue;" class="fw-bold">Q.</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-11 col-md-11 col-11 fw-bold">
                                        <div class="text-start">
                                            <span>
                                                <?php h($row['question']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-1">
                                        <div class="text-end">
                                            <span style="color: red; font-weight: bold;">A.</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-11 col-md-11 col-11">
                                        <div class="text-start">
                                            <span>
                                                <?php echo preg_replace('/\n/', "<br>",  $row['answer']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-1 col-md-1 col-2">
                                    <?php $like_check = $lik_calc->es_experience_like_check($row['post_id'], $user_id); ?>
                                    <?php $like_val = $lik_calc->es_experience_like_count($row['post_id']); ?>

                                    <?php if ($like_check) : ?>
                                        <form action="./posts.php" method="post">
                                            <input type="hidden" name="post_id" value="<?php h($row['post_id']) ?>">
                                            <input type="hidden" name="student_id" value="<?php h($row['student_id']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?php h($ses_calc->create_csrf_token()); ?>">
                                            <button class="btn fs-5" name="like_delete">
                                                <i style="color: red;" class="bi bi-heart-fill"></i>
                                            </button>
                                        </form>
                                    <?php else : ?>
                                        <form action="./posts.php" method="post">
                                            <input type="hidden" name="post_id" value="<?php h($row['post_id']) ?>">
                                            <input type="hidden" name="student_id" value="<?php h($row['student_id']) ?>">
                                            <input type="hidden" name="csrf_token" value="<?php h($ses_calc->create_csrf_token()); ?>">
                                            <button class="btn fs-5" name="like">
                                                <i style="color: red;" class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-4 col-md-4 col-6 mt-2">
                                    <span class="fs-6">いいね数：<?php h($like_val) ?></span>
                                </div>

                                <div class="col-lg-7 col-md-7 col-12 text-end mt-2">
                                    <?php h($row['name']) ?> ｜ <?php h($row['course_of_study']) ?> ｜ <?php h($row['grade_in_school']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php if ($page > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php h($page - 1); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;<?php h($page - 1); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page < $pagination) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php h($page + 1); ?>" aria-label="Next">
                                    <span aria-hidden="true"><?php h($page + 1); ?>&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>

            <div class="side-bar col-md-12 col-12 col-lg-4 bg-light h-100">
                <div class="d-flex flex-column flex-shrink-0 p-3 bg-light">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="../intern_information/posts_recommendation.php" class="nav-link link-dark">
                                インターンシップ情報
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../briefing_information/posts_recommendation.php" class="nav-link link-dark">
                                会社説明会情報
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../intern_experience/posts.php" class="nav-link link-dark">
                                インターンシップ体験記
                            </a>
                        </li>

                        <li>
                            <a href="./posts.php" style="background-color: #EB6440;" class="nav-link active" aria-current="page">
                                ES体験記
                            </a>
                        </li>

                        <li>
                            <a href="../intern_experience/post/post_form.php" class="nav-link link-dark">
                                インターンシップ体験記を投稿
                            </a>
                        </li>

                        <li>
                            <a href="./post/post_form.php" class="nav-link link-dark">
                                ES体験記を投稿
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <div class="dropdown">
                        <div class="mb-4">
                            <form action="./search/search_result.php" method="post">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" placeholder="フリーワード検索">
                                    <input type="hidden" name="category" value="answer">
                                    <button class="btn btn-outline-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class="mb-4">
                            <form action="./search/search_result.php" method="post">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" placeholder="企業名で検索">
                                    <input type="hidden" name="category" value="company">
                                    <button class="btn btn-outline-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class="mb-4">
                            <form action="./search/search_result.php" method="post">
                                <div class="input-group">
                                    <select class="form-select" name="keyword" aria-label="Default select example">
                                        <option selected>質問内容で検索</option>
                                        <option value="学校で頑張ったことを教えてください。">学校で頑張ったことを教えてください。</option>
                                        <option value="志望理由を教えてください。">志望理由を教えてください。</option>
                                    </select>
                                    <input type="hidden" name="category" value="question">
                                    <button class="btn btn-outline-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div>
                            <form action="./search/search_result.php" method="post">
                                <div class="input-group">
                                    <select class="form-select" name="keyword" aria-label="Default select example">
                                        <option selected>業界で検索</option>
                                        <option value="IT分野">IT分野</option>
                                        <option value="ゲームソフト分野">ゲームソフト分野</option>
                                        <option value="ハード分野">ハード分野</option>
                                        <option value="ビジネス分野">ビジネス分野</option>
                                        <option value="CAD分野">CAD分野</option>
                                        <option value="グラフィックス分野">グラフィックス分野</option>
                                        <option value="サウンド分野">サウンド分野</option>
                                        <option value="日本語分野">日本語分野</option>
                                        <option value="国際コミュニケーション分野">国際コミュニケーション分野</option>
                                    </select>
                                    <input type="hidden" name="category" value="field">
                                    <button class="btn btn-outline-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if ($user_icon == "") : ?>
                                <img src="../../../../public/ICON/default-icon.jpeg" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                            <?php else : ?>
                                <img src="../../../../public/ICON/<?php h($user_icon) ?>" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                            <?php endif; ?>
                            <strong><?php h($user_name) ?></strong>
                        </a>
                        <ul class="dropdown-menu text-small shadow">
                            <li><a class="dropdown-item" href="../profile/profile.php">プロフィール</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../../../logout/logout.php">ログアウト</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-3">
        <div class="text-light text-center small">
            &copy; 2022 Toge-Company, Inc
            <a class="text-white" target="_blank" href="https://hayate-takeda.xyz/">hayate-takeda.xyz</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
</body>

</html>