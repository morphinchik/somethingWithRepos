<div id="content-page", class="content group">
	<div class="hentry group">
		<h3 class="title_page">Привелегии</h3>
		<form action="{{ route('admin.permissions.store') }}" method="POST">
			{{ csrf_field() }}
			<div class="short-table white">
				<table style="width: 100%">
					<thead>
						<th>Привелегии</th>
						@if(!$roles->isEmpty())
							@foreach($roles as $item)
								<th>{{ $item->name }}</th>
							@endforeach
						@endif
					</thead>
					<tbody>
						@if(!$permissions->isEmpty())
							@foreach($permissions as $val)
								<tr>
									<td>{{ $val->name }}</td>
										@foreach($roles as $role)
											<td>
												@if($role->hasPermission($val->name))
													<input type="checkbox" name="{{ $role->id }}[]" value="{{ $val->id }}" checked>
												@else
													<input type="checkbox" name="{{ $role->id }}[]" value="{{ $val->id }}">
												@endif
											</td>
										@endforeach
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
			<input type="submit" class="btn btn-the-salmon-dance-5" name="" value="Обновить привелегии">
		</form>
	</div>
</div>