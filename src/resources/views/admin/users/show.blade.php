@extends('layouts.app')

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">会員一覧</a></li>
                        <li class="breadcrumb-item active" aria-current="page">会員詳細</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">{{ $user->name }}</h1>

                <div class="container mb-4">
                    @php
                        $rows = [
                            'ID' => $user->id,
                            '氏名' => $user->name,
                            'フリガナ' => $user->kana,
                            'メールアドレス' => $user->email,
                            '郵便番号' => substr($user->postal_code, 0, 3) . '-' . substr($user->postal_code, 3),
                            '住所' => $user->address,
                            '電話番号' => $user->phone_number,
                        ];
                    @endphp

                    @foreach ($rows as $label => $value)
                        <div class="row pb-2 mb-2 border-bottom">
                            <div class="col-3">
                                <span class="fw-bold">{{ $label }}</span>
                            </div>
                            <div class="col">
                                <span>{{ $value }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">誕生日</span>
                        </div>
                        <div class="col">
                            <span>
                                @if ($user->birthday !== null)
                                    {{ date('n月j日', strtotime($user->birthday)) }}
                                @else
                                    未設定
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">職業</span>
                        </div>
                        <div class="col">
                            <span>
                                @if ($user->occupation !== null)
                                    {{ $user->occupation }}
                                @else
                                    未設定
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">会員種別</span>
                        </div>
                        <div class="col">
                            <span>
                                @if ($user->subscribed('premium_plan'))
                                    有料会員
                                @else
                                    無料会員
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection