<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// class ItemsExport implements FromCollection
// {
//     *
//     * @return \Illuminate\Support\Collection
    
//     public function collection()
//     {
//         return User::all();
//     }
// }

class ItemsExportAll implements FromCollection, WithHeadings, ShouldAutoSize
{
     use Exportable;
	public $errors;

	public function __construct($errors){
		$this->errors = $errors;
	}
    
    public function collection()
    {
        $errors = $this->errors;
	    return collect($errors);
    }
    public function headings(): array
	{
        return [
             'titles',
            'hsn_code',
            'category_name',
            //'subcategory_name',
            'department',
            'units',
            'item_type',
            'description',
            'vendor_name',
            'part_number',
            'rate',
            'gst',
            'invoice_no',
            'invoice_date',
            'contact',
            'gst_no',
            'location',
            'email_id',
            'error_filed',

        ];
	}
}
