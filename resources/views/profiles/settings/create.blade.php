@extends('layouts.app')

@section('hero')

  <div class="tw-h-48 sm:tw-h-64 tw-bg-green tw-px-6">
    <div class="container">
      <div class="tw-text-3xl tw-text-white tw-h-full tw-flex tw-items-end tw-pb-4">
        Settings
      </div>
    </div>
  </div>

@endsection

@section('content')

<div class="tw-w-full tw-bg-white tw-rounded tw-p-4 sm:tw-p-6 offset-t">
  <div class="columns">

    <div class="column is-1 tw-flex tw-items-center ">
    </div>{{-- end empty column spacer--}}

    <div class="column tw-flex tw-items-center sm:tw-justify-start tw-justify-center">
      Upload a profile picture? 
    </div>{{-- end description col --}}

    <div class="column is-4">
      <div class="tw-flex tw-flex-col settings__img-box tw-justify-between tw-items-center">

        <span class="tw-text-2xl"> Upload Avatar </span>

        <img src="https://imgplaceholder.com/420x320" alt="profile picture" class="tw-h-48 tw-w-48 ">

        <div class="file">
          <label class="file-label">
            <input class="file-input" type="file" name="avatar">
            <span class="file-cta">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Choose a file…
              </span>
            </span>
          </label>
        </div>
        
      </div>

    </div>{{-- end image col --}}
  </div>

  <hr>

  <div class="columns"> 
    <div class="column is-1 tw-flex tw-items-center ">
    
    </div>{{-- end number col --}}

    <div class="column tw-flex tw-items-center sm:tw-justify-start tw-justify-center">
      Display Activity Feed?
    </div>{{-- end description col --}}

    <div class="column is-4">
      <div class="tw-flex tw-flex-col settings__img-box tw-justify-center tw-items-center">

        <label class="checkbox">
          <input type="checkbox" name="activity_feed">
        </label>

      </div>

    </div>{{-- end image col --}}
  </div>

</div>

@endsection


<style>
  .settings__img-box {
    width: 100%;
    height: 300px;
  }

</style>