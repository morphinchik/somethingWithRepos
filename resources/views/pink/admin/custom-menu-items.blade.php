@foreach($items as $item)
	<tr>
		<td style="text-align: left;">{{ $paddingLeft }} {!! Html::link(route('admin.menus.edit', ['menu' => $item->id]), $item->title) !!}</td>
		<td>{{ $item->url()}}</td>
		<td>
			{!! Form::open(['url' => route('admin.menus.destroy', ['menu' => $item->id]), 'class' => 'form-horizontal', 'method' => 'POST']) !!}
				{{ method_field('DELETE') }}
				{!! Form::button('Удалить', ['class' => 'btn btn-the-salmon-dance-5', 'type' => 'submit']) !!}
			{!! Form::close() !!}
		</td>
	</tr>
	@if($item->hasChildren())
		@include(env('THEME').'.admin.custom-menu-items', ['items' => $item->children(), 'paddingLeft' => $paddingLeft.'--'])
	@endif

@endforeach