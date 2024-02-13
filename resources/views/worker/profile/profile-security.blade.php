@extends('layouts.afterlogin')
@section('content')
<div class="container-fluid">
    <!-- all games start -->
    <div class="all-history-page all-surveys-page">
        <!-- ***** Profile code Start ***** -->
        <!-- Profle security settings start -->
        <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                {{-- <a class="nav-link  ms-0" href="/profile">Profile</a> --}}
                <a class="nav-link" href="/profile/payout-methods">Billing</a>
                <a class="nav-link active" href="/profile/security">Security</a>
            </nav>
            <hr class="mt-0 mb-4">
            <div class="row">
                @livewire('auth.update-password')
                {{-- <div class="col-lg-4"> --}}
                    {{-- @livewire('auth.two-factor-authentication-setup') --}}
                    {{-- <!-- Two factor authentication card-->
                    <div class="card mb-4">
                        <div class="card-header">Two-Factor Authentication</div>
                        <div class="card-body">
                            <p>Add another level of security to your account by enabling two-factor authentication. We
                                will send you a text message to verify your login attempts on unrecognized devices and
                                browsers.</p>
                            <form>
                                <div class="form-check">
                                    <input class="form-check-input" id="twoFactorOn" type="radio" name="twoFactor"
                                        checked="">
                                    <label class="form-check-label" for="twoFactorOn">On</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="twoFactorOff" type="radio" name="twoFactor">
                                    <label class="form-check-label" for="twoFactorOff">Off</label>
                                </div>
                                <div class="mt-3">
                                    <label class="small mb-1" for="twoFactorSMS">SMS Number</label>
                                    <input class="form-control" id="twoFactorSMS" type="tel"
                                        placeholder="Enter a phone number" value="555-123-4567">
                                </div>
                            </form>
                        </div>
                    </div> --}}
                {{-- </div> --}}
            </div>
        </div>
        <!-- Profile billing settings end -->
        <!-- ***** Profile code End ***** -->
    </div>
</div>
@endsection