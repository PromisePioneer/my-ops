@props([
    'colspan' => null,
])


<tbody class="fw-bold">
<tr>
    <td colspan="{{ $colspan }}">
        <div style="text-align: center;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </td>
</tr>
</tbody>
