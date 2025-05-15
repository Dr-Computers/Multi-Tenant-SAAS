<h2>Welcome, {{ $user->name }}!</h2>
<p>Your staff account has been successfully created.</p>

<p><strong>Email:</strong> {{ $user->email }}</p>
@if(isset($user->company))
    <p><strong>Company:</strong> {{ $user->company->name }}</p>
@endif

<p>Please log in to the system using your credentials.</p>
