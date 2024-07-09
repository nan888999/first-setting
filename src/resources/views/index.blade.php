@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header')
  <nav class="header-nav">
    <ul class="header-nav-list">
      <li class="header-nav-item"><a href="/">ホーム</a></li>
      <li class="header-nav-item"><a href="/attendance">日付一覧</a></li>
      <li class="header-nav-item">
        <form class="logout" action="{{route('user.logout')}}" method="post">
          @csrf
          <input class="logout" type="submit" value="ログアウト">
        </form>
      </li>
    </ul>
  </nav>
@endsection

@section('content')
<div class="content__title">{{\Illuminate\Support\Facades\Auth::user()->name}}さんお疲れ様です！</div>
<div class="content__item">
  <form action="/workStart" method="post">
    @csrf
    @if($workStart->isEmpty())
      <button type="submit" class="available">勤務開始</button>
      @else
      <button type="button" class="non-available">勤務開始</button>
    @endif
  </form>
  <form action="/workEnd" method="post">
    @csrf
    @if(!$workStart->isEmpty() && $workStart->last()->created_at == $workEnd->last()->updated_at)
      <button type="submit" class="available">勤務終了</button>
      @else
      <button type="button" class="non-available">勤務終了</button>
    @endif
  </form>
</div>
<div class="content__item">
  <form action="/breakStart" method="post">
    @csrf
    @if(!$workStart->isEmpty() && ($workStart->last()->created_at == $workEnd->last()->updated_at) && ($breakStart->isEmpty() || $breakStart->last()->created_at != $breakEnd->last()->updated_at))
      <button type="submit" class="available">休憩開始</button>
      @else
      <button type="button" class="non-available">休憩開始</button>
    @endif
  </form>
  <form action="/breakEnd" method="post">
    @csrf
    @if(!$breakStart->isEmpty() && $breakStart->last()->created_at == $breakEnd->last()->updated_at)
      <button type="submit" class="available">休憩終了</button>
      @else
      <button type="button" class="non-available">休憩終了</button>
    @endif
  </form>
</div>
@endsection