<div class="form-group">
	<div>
    <label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
    <div class="col-sm-9">
    qrcode link: 
    <a 
    target="_blank"
    href="https://image-charts.com/chart?chs=450x450&cht=qr&choe=UTF-8&chl={{$field->format($model)}}">
    https://image-charts.com/chart?chs=450x450&cht=qr&choe=UTF-8&chl={{$field->format($model)}}
    </a>
    </div>
  </div>
  <div class="col-sm-12 text-center">
  <img src="https://image-charts.com/chart?chs=450x450&cht=qr&choe=UTF-8&chl={{$field->format($model)}}" />
  </div>
</div>
