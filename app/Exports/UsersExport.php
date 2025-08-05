<?php
namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = User::query();

        if ($this->request->filled('name')) {
            $query->where('name', 'like', '%' . $this->request->name . '%');
        }

        if ($this->request->filled('email')) {
            $query->where('email', 'like', '%' . $this->request->email . '%');
        }

        return $query->get(['id', 'name', 'email']);
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email'];
    }
}
