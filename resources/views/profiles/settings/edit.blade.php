@extends('layouts.app')

@section('hero')

  <div class="tw-h-48 sm:tw-h-64 tw-bg-bulma tw-px-6">
    <div class="container">
      <div class="tw-text-3xl tw-text-white tw-h-full tw-flex tw-items-end tw-pb-4">
        Settings
      </div>
    </div>
  </div>

@endsection

@section('content')

<form action={{ route('profiles.settings.update', $user) }} method="POST" enctype="multipart/form-data">
  @csrf

  <div class="tw-w-full tw-bg-white tw-rounded tw-p-4 sm:tw-p-6 offset-t">
    <div class="columns">
  
      <div class="column is-1">
      </div>{{-- end empty column spacer--}}
  
      <div class="column tw-flex tw-items-center sm:tw-justify-start tw-justify-center">
        <div>
          Upload a profile photo?
          <br>
          <span class="tw-text-xs"> Note: This will also serve as your avatar </span>
        </div>
      </div>{{-- end description col --}}
  
      <div class="column is-4">

      <lu-avatar-uploader 
        :endpoint= {{ json_encode( route('api.uploads.images.store', ['avatars',$user], false) ) }} 
        :current-avatar={{  json_encode( $user->profile->profile_photo_path  ) }}
        send-as="file">
      </lu-avatar-uploader>

      </div>{{-- end image col --}}
    </div>{{-- end profile image row --}}
  
    <hr class="tw-my-4">
  
    <div class="columns"> 
      <div class="column is-1">
      
      </div>{{-- end number col --}}
  
      <div class="column tw-flex tw-items-center sm:tw-justify-start tw-justify-center">
        Hide Activity Feed?
      </div>{{-- end description col --}}
  
      <div class="column is-4">
        <div class="tw-flex tw-flex-col settings__img-box tw-justify-center tw-items-center">
  
          <label class="checkbox">
          <input type="checkbox" name="hide_activities" value="1" {{ $user->profile->hide_activities ? 'checked' : '' }}>
          </label>
  
        </div>
  
      </div>{{-- end hide activity feed column --}}
    </div>{{-- end hide activity feed row --}}
  
    <hr class="tw-my-4">
  
    <div class="tw-flex tw-justify-center">
      <button class="button is-primary is-rounded is-medium tw-shadow"> Update My Profile </button>
    </div>
    {{-- end update profile button --}}
  
  </div>
</form>

@endsection


<style>
  .settings__img-box {
    width: 100%;
    height: 300px;
  }

</style>