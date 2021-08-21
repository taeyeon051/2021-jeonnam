<!-- 로그인 영역 -->
<section id="login-page">
    <h3 class="text-center mb-5">로그인</h3>
    <form action="/login" method="POST">
        <div class="row mb-5">
            <label for="userId" class="col-sm-2 col-form-label">아이디</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="userId" id="userId">
            </div>
        </div>
        <div class="row mb-5">
            <label for="userPwd" class="col-sm-2 col-form-label">비밀번호</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="userPwd" id="userPwd">
            </div>
        </div>
        <button class="btn btn-brown float-end">로그인</button>
    </form>
</section>