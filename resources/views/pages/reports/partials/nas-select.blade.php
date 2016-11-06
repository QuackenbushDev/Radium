<select name="nasID" id="nasIDSelectList">
    @foreach($nasList as $nas)
        <option value="$nas->id">$nas->nasname</option>
    @endforeach
</select>