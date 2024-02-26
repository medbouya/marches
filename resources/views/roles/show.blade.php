@extends('layout')

@section('content')
<div class="container">
    <h2>Permissions du groupe: {{ $role->name }}</h2>
    <div class="row">
      <hr/>
      <div class="col-md-5">
        <h5>Permissions disponibles</h5>
        <select id="permissionsAvailable" class="form-control" multiple style="height: 200px;">
          @foreach($permissions as $permission)
            @unless($role->hasPermissionTo($permission->name))
              <option value="{{ $permission->name }}">{{ $permission->name }}</option>
            @endunless
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-center justify-content-center">
        <div>
          <button type="button" id="assignPermission" class="btn btn-primary mb-2">Assigner →</button>
          <button type="button" id="removePermission" class="btn btn-danger">← Supprimer</button>
        </div>
      </div>
      <div class="col-md-5">
          <h5>Permissions assignées</h5>
          <select id="permissionsAssigned" class="form-control" multiple style="height: 200px;">
              @foreach($permissions as $permission)
                  @if($role->hasPermissionTo($permission->name))
                      <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                  @endif
              @endforeach
          </select>
      </div>
    </div>

    <form method="POST" action="{{ route('roles.updatePermissions', $role) }}" id="permissionsForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="permissions" id="permissionsValue">
        <button type="submit" class="btn btn-success btn-sm mt-3">Mettre à jour les permissions</button>
    </form>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const assignBtn = document.getElementById('assignPermission');
  const removeBtn = document.getElementById('removePermission');
  const available = document.getElementById('permissionsAvailable');
  const assigned = document.getElementById('permissionsAssigned');
  const permissionsForm = document.getElementById('permissionsForm');
  const permissionsValue = document.getElementById('permissionsValue');

  assignBtn.addEventListener('click', function () {
      moveSelectedOptions(available, assigned);
  });

  removeBtn.addEventListener('click', function () {
      moveSelectedOptions(assigned, available);
  });

  permissionsForm.addEventListener('submit', function (e) {
      const selectedPermissions = [];
      for (let option of assigned.options) {
          selectedPermissions.push(option.value);
      }
      permissionsValue.value = JSON.stringify(selectedPermissions);
  });

  function moveSelectedOptions(from, to) {
      Array.from(from.selectedOptions).forEach(option => {
          to.appendChild(option);
      });
  }
});
</script>
@endsection
