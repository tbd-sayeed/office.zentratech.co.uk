@php $currencies = \App\Helpers\CurrencyHelper::all(); $selected = $value ?? old(str_replace(['[', ']'], ['.', ''], $name)); @endphp
<select name="{{ $name }}" @if(isset($id)) id="{{ $id }}" @endif class="form-select {{ $class ?? '' }}" {{ $required ?? false ? 'required' : '' }}>
    @foreach($currencies as $code => $info)
    <option value="{{ $code }}" {{ $selected == $code ? 'selected' : '' }}>{{ $info['symbol'] }} {{ $code }}</option>
    @endforeach
</select>
