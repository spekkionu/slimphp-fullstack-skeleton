<?php $messages = flash_messages('success'); ?>
@if($messages)
<div class="alert alert-success">
    @foreach($messages as $message)
    <div>{{ $message }}</div>
    @endforeach
</div>
@endif

<?php $messages = flash_messages('info'); ?>
@if($messages)
<div class="alert alert-info">
    @foreach($messages as $message)
    <div>{{ $message }}</div>
    @endforeach
</div>
@endif

<?php $messages = flash_messages('error'); ?>
@if($messages)
<div class="alert alert-danger">
    @foreach($messages as $message)
    <div>{{ $message }}</div>
    @endforeach
</div>
@endif

<?php $messages = flash_messages('warning'); ?>
@if($messages)
<div class="alert alert-warning">
    @foreach($messages as $message)
    <div>{{ $message }}</div>
    @endforeach
</div>
@endif
