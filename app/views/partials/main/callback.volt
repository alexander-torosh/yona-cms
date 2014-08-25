<div id="callback">
    <div class="icon"></div>
    <div class="form">
        <div class="close"></div>
        <form action="" onsubmit="return callbackSubmit(this);">
            <input type="text" name="name" placeholder="*Ваше имя:" required="required">
            <input type="email" name="email" placeholder="*Ваш e-mail:" required="required">
            <input type="text" name="phone" placeholder="*Ваш телефон:" required="required">
            <textarea name="message" id="callback-text" placeholder="Ваш вопрос:"></textarea>
            <input type="submit" value="Отправить">
            <section class="desc">*Поля обязательные для заполнения</section>
        </form>
    </div>
</div>