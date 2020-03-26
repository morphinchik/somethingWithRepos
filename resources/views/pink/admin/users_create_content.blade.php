<div id="content-page" class="content group">
	<div class="hentry group">
		{!! Form::open(['url' => (isset($user->id)) ? route('admin.users.update', ['user' => $user->id]) : route('admin.users.store'), 'class' => 'contact-form', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
			@if(isset($user->id))
					<input type="hidden" name="_method" value="PUT">
			@endif
			<ul>
				<li class="text-field">
					<label for="name-contact-us">
						<span class="label">Имя:</span><br />
						<span class="sublabel">Имя</span><br />
					</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						{!! Form::text('name', isset($user->name) ? $user->name : old('name'), ['placeholder' => 'Введите имя пользователя']) !!}
					</div>
				</li>
				<li class="text-field">
					<label for="name-contact-us">
						<span class="label">Логин:</span><br />
						<span class="sublabel">Логин</span><br />
					</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						{!! Form::text('login', isset($user->login) ? $user->login : old('login'), ['placeholder' => 'Введите логин пользователя']) !!}
					</div>
				</li>
				<li class="text-field">
					<label for="name-contact-us">
						<span class="label">Email:</span><br />
						<span class="sublabel">Email</span><br />
					</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						{!! Form::email('email', isset($user->email) ? $user->email : old('email'), ['placeholder' => 'Введите email пользователя']) !!}
					</div>
				</li>
				<li class="text-field">
					<label for="name-contact-us">
						<span class="label">Пароль:</span><br />
						<span class="sublabel">Введите пароль</span><br />
					</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						{!! Form::password('password') !!}
					</div>
				</li>
				<li class="text-field">
					<label for="name-contact-us">
						<span class="label">Повтор пароля:</span><br />
						<span class="sublabel">Повтор пароля</span><br />
					</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						{!! Form::password('password_confirmation') !!}
					</div>
				</li>
				<li class="text-field">
					<label for="name-contact-us">
						<span class="label">Повтор пароля:</span><br />
						<span class="sublabel">Повтор пароля</span><br />
					</label>
					<div class="input-prepend">
						{!! Form::select('role_id', $roles, isset($user) ? $user->roles()->first()->id : null) !!}
					</div>
				</li>

				<li class="submit-button">
					{!! Form::button('Сохранить', ['class' => 'btn btn-the-salmon-dance-5', 'type' => 'submit']) !!}
				</li>
			</ul>	

	{!! Form::close() !!} 
	</div>
</div>