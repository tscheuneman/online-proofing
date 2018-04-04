<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\PdfToImage\Exceptions\PageDoesNotExist;
use Spatie\PdfToImage\Pdf;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;

use Storage;
use File;

use App\Entry;
use Intervention\Image\ImageManager;

use Mockery\Exception;

class ConvertPDF implements ShouldQueue
{
    public $tries = 3;
    public $timeout = 50;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dir, $storageName, $width;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dir, $storageName, $width = 60)
    {
        $this->dir = $dir;
        $this->storageName = $storageName;
        $this->width = $width;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $realPath = realpath(public_path('/storage/' . $this->dir . '/pdf/' . $this->storageName));
            \Log::info($realPath);

            try {
                if($pdf = new Pdf(public_path('/storage/' . $this->dir . '/pdf/' . $this->storageName))){
                    return "Test";
                }
                else {
                    throw new PageDoesNotExist();
                }

            } catch(\Exception $e) {
                report($e);
            }



            /*
            $numPages = $pdf->getNumberOfPages();
            for($x = 0; $x < $numPages; $x++) {
                $pdf->setPage($x)
                    ->saveImage(storage_path() . '/app/' . $this->dir . '/' . $x);
            }

            \Log::info($numPages);

            return 'test';
*/
        }
        catch (Exception $e) {
            report(storage_path() . '/app/projects/' . $this->dir . '/pdf/' . $this->storageName);
        }
    }
}
