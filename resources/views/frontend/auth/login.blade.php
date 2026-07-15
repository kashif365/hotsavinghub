@extends('frontend.layouts.app')

@section('title', 'Customer Login | Hotsavinghub')
@section('description', 'Login to your account to access exclusive deals and discounts.')

@section('content')



<div class="Sec bg">
    <div class="splt Wrp">
        <div class="wgtc">
            <div class="auth-container">
                <div class="auth-form">
                    <h2>Sign In</h2>
                    <p>Welcome back! Please sign in to your account.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <div class="form-group checkbox-group">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>

                        <button type="submit" class="btn-primary">Sign In</button>
                    </form>

                    <div class="auth-links">
                        <p>Don't have an account? <a href="{{ route('customer.register') }}">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.auth-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 40px 20px;
}

.auth-form {
    background: var(--background-primary-color, #fff);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.auth-form h2 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-color, #333);
    margin-bottom: 10px;
    text-align: center;
}

.auth-form p {
    color: var(--text-color, #666);
    text-align: center;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--text-color, #333);
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--background-secondary-color, #e1e5e9);
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color, #FF0000);
}

.checkbox-group {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-size: 14px;
    cursor: pointer;
}

.checkbox-group input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
}

.btn-primary {
    width: 100%;
    background: var(--primary-color, #FF0000);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: var(--secondary-color, #e60000);
}

.auth-links {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid var(--background-secondary-color, #e1e5e9);
}

.auth-links p {
    margin: 0;
    color: var(--text-color, #666);
}

.auth-links a {
    color: var(--primary-color, #FF0000);
    text-decoration: none;
    font-weight: 500;
}

.auth-links a:hover {
    text-decoration: underline;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

.alert-danger {
    background: var(--background-secondary-color, #f8d7da);
    color: var(--text-color, #721c24);
    border: 1px solid var(--background-secondary-color, #f5c6cb);
}

.wgt ul {
    list-style: none;
    padding: 0;
}

.wgt li {
    padding: 8px 0;
    color: var(--text-color, #666);
    position: relative;
    padding-left: 20px;
}

.wgt li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--primary-color, #FF0000);
    font-weight: bold;
}

@media (max-width: 768px) {
    .auth-container {
        padding: 20px 10px;
    }

    .auth-form {
        padding: 30px 20px;
    }

    .auth-form h2 {
        font-size: 24px;
    }
}
</style>
@endsection
