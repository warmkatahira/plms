<x-app-layout>
    <div class="mt-5">
        <div class="flex flex-row items-end">
            <img src="{{ asset('storage/profile_images/' . $user->profile_image_file_name) }}" class="w-24 mr-10">
            <div class="flex flex-row mt-3">
                <button type="button" id="profile_image_update_modal_open" class="btn bg-theme-main text-sm p-3"><i class="las la-image la-lg mr-1"></i>プロフィール画像更新</button>
            </div>
        </div>
        <form method="POST" action="{{ route('profile_update.update') }}" id="profile_update_form">
            @csrf
            <div class="flex flex-col mt-5">
                <x-form.input type="text" label="姓" id="last_name" name="last_name" :value="$user->last_name" required="true" />
                <x-form.input type="text" label="名" id="first_name" name="first_name" :value="$user->first_name" required="true" />
                <x-form.input type="text" label="メールアドレス" id="email" name="email" :value="$user->email" required="true" />
                <x-form.input type="tel" label="1ページあたりの表示数" id="per_page" name="per_page" :value="$user->per_page" required="true" />
            </div>
        </form>
        <div class="flex flex-row gap-10 mt-5">
            <button id="profile_update_enter" type="button" class="btn bg-btn-enter text-white w-56 p-3"><i class="las la-check la-lg mr-1"></i>プロフィール更新</button>
            <button id="logout_enter" type="button" class="btn bg-btn-cancel text-white w-56 p-3"><i class="las la-sign-out-alt la-lg mr-1"></i>ログアウト</button>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logaut_form" class="mt-5">
            @csrf
        </form>
    </div>
    <!-- プロフィール画像変更モーダル -->
    <x-profile.profile-image-update-modal />
</x-app-layout>
@vite(['resources/js/profile/profile.js', 'resources/sass/profile/profile.scss'])