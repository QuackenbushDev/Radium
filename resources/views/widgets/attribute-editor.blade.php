<div class="col-md-6 col-sm-12"
     ng-controller="AttributeEditorController"
     ng-init="init('{{ $type }}', '{{ $username }}', '{{ $groupName }}')"
>
    <h4>{{ $title }}</h4>

    <div class="form-group">
        <table class="table table-responsive table-bordered">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>OP</th>
                    <th>Value</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="attribute in attributes track by $index">
                    <td>
                        <input type="hidden"
                               name="attributes[@{{ type }}][@{{ attribute.attribute }}][id]"
                               ng-value="attributes[$index].id"
                        >
                        <input type='text'
                               ng-value="attributes[$index].attribute"
                               disabled
                        >
                    </td>
                    <td>
                        <select name="attributes[@{{ type }}][@{{ attribute.attribute }}][op]"
                                class="input-sm"
                                ng-model="attributes[$index].op"
                        >
                            <option></option>
                            <option>=</option>
                            <option>:=</option>
                            <option>==</option>
                            <option>+=</option>
                            <option>!=</option>
                            <option>></option>
                            <option>>=</option>
                            <option><</option>
                            <option><=</option>
                            <option>=~</option>
                            <option>!~</option>
                            <option>=*</option>
                            <option>!*</option>
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               name="attributes[@{{ type }}][@{{ attribute.attribute }}][value]"
                               ng-value="attributes[$index].value"
                        >
                    </td>
                    <td class="text-bold">
                        <a ng-click="deleteAttribute($index)">DELETE</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <h4>Add a new attribute</h4>
        <select class="input-sm"
                ng-model="vendor"
                ng-options="vendor for vendor in vendors"
        ></select>

        <select class="input-sm"
                ng-model="attribute"
                ng-options="entry for entry in dictionary[vendor]"
        ></select>

        <select class="input-sm" ng-model="op">
            <option></option>
            <option>=</option>
            <option>:=</option>
            <option>==</option>
            <option>+=</option>
            <option>!=</option>
            <option>></option>
            <option>>=</option>
            <option><</option>
            <option><=</option>
            <option>=~</option>
            <option>!~</option>
            <option>=*</option>
            <option>!*</option>
        </select>

        <input class="input-sm" type="text" ng-model="value" />

        <a class="btn btn-sm btn-success" ng-click="addAttribute()">Add Attribute</a>
    </div>

    <input ng-repeat="id in deleted" type="hidden" name="deleted[@{{ type }}][]" value="@{{ id }}">
</div>