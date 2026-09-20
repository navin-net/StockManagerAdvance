<?php

namespace App\Imports\Admin;

use App\Models\Products;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\SubCategory;
use App\Models\Units;
use App\Models\Qualitys;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ProductsImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, SkipsOnError
{
    use SkipsErrors;

    protected array $skippedRows = [];
    protected int $createdCount = 0;
    protected int $updatedCount = 0;

    /**
     * Expected spreadsheet headers (row 1), case-insensitive, spaces become underscores:
     * name | code | description | stock_quantity | expiry_date | cost_price |
     * selling_price | second_name | unit | brand | category | subcategory | quality
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $row) {
            $rowData = [
                'name'            => trim($row['name'] ?? ''),
                'code'            => trim($row['code'] ?? ''),
                'description'     => $row['description'] ?? null,
                'stock_quantity'  => $row['stock_quantity'] ?? 0,
                'expiry_date'     => $this->parseDate($row['expiry_date'] ?? null),
                'cost_price'      => $row['cost_price'] ?? null,
                'selling_price'   => $row['selling_price'] ?? null,
                'second_name'     => $row['second_name'] ?? null,
                'unit'            => trim($row['unit'] ?? ''),
                'brand'           => trim($row['brand'] ?? ''),
                'category'        => trim($row['category'] ?? ''),
                'subcategory'     => trim($row['subcategory'] ?? ''),
                'quality'         => trim($row['quality'] ?? ''),
            ];

            $validator = Validator::make($rowData, [
                'name'           => ['required', 'string', 'max:255'],
                'code'           => ['required', 'string', 'max:100'],
                'stock_quantity' => ['nullable', 'integer', 'min:0'],
                'cost_price'     => ['required', 'numeric', 'min:0'],
                'selling_price'  => ['required', 'numeric', 'min:0'],
                'expiry_date'    => ['nullable', 'date'],
            ]);

            if ($validator->fails()) {
                $this->skippedRows[] = [
                    'row'    => $index + 2,
                    'code'   => $rowData['code'] ?: null,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            if (Products::where('code', $rowData['code'])->exists()) {
                $this->skippedRows[] = [
                    'row'    => $index + 2,
                    'code'   => $rowData['code'],
                    'errors' => ['A product with this code already exists.'],
                ];
                continue;
            }

            $unitId        = $this->resolveId(Units::class, $rowData['unit']);
            $brandId       = $this->resolveId(Brand::class, $rowData['brand']);
            $categoryId    = $this->resolveId(Categories::class, $rowData['category']);
            $subcategoryId = $this->resolveId(SubCategory::class, $rowData['subcategory']);
            $qualityId     = $this->resolveId(Qualitys::class, $rowData['quality']);

            Products::create([
                'name'            => $rowData['name'],
                'code'            => $rowData['code'],
                'description'     => $rowData['description'],
                'stock_quantity'  => $rowData['stock_quantity'] ?: 0,
                'expiry_date'     => $rowData['expiry_date'],
                'cost_price'      => $rowData['cost_price'],
                'selling_price'   => $rowData['selling_price'],
                'second_name'     => $rowData['second_name'],
                'unit_id'         => $unitId,
                'brand_id'        => $brandId,
                'category_id'     => $categoryId,
                'subcategory_id'  => $subcategoryId,
                'quality_id'      => $qualityId,
            ]);

            $this->createdCount++;
        }
    }

    /**
     * Find a related model by name, creating it if it doesn't exist.
     * Remove the firstOrCreate fallback if you'd rather skip rows with unknown relations.
     */
    protected function resolveId(string $modelClass, ?string $name): ?int
    {
        if (empty($name)) {
            return null;
        }

        return $modelClass::firstOrCreate(['name' => $name])->id;
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Handles Excel numeric date serials as well as string dates
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }

    /**
     * Summary consumed by the controller's JSON response.
     */
    public function getMessages(): array
    {
        // SkipsErrors trait collects Throwables raised during row processing
        $failureMessages = collect($this->errors())
            ->map(fn ($e) => $e->getMessage())
            ->toArray();

        return [
            'created'        => $this->createdCount,
            'updated'        => $this->updatedCount,
            'skipped_count'  => count($this->skippedRows),
            'skipped_rows'   => $this->skippedRows,
            'error_messages' => $failureMessages,
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
