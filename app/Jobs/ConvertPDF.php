<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotify;

use Storage;
use File;
use Imagick;

use App\Entry;
use App\Project;
use App\UserAssign;
use App\Order;

use Mockery\Exception;

class ConvertPDF implements ShouldQueue
{
    public $tries = 3;
    public $timeout = 500;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dir, $storageName, $width, $project, $entry;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dir, $storageName, $width = 60, Project $project, Entry $entry)
    {
        $this->dir = $dir;
        $this->storageName = $storageName;
        $this->width = $width;
        $this->project = $project;
        $this->entry = $entry;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $realPath = public_path('/storage/' . $this->dir . '/pdf/' . $this->storageName);

            if(File::makeDirectory( public_path('/storage/' . $this->dir . '/images'), 0775, true)) {
                $savePath = public_path('/storage/' . $this->dir . '/images/');
                try {

                    $im = new Imagick();
                    $files = array();
                    $im->setResolution(220,220);
                    $im->readimage($realPath);
                    $numPages = $im->getNumberImages();

                    for($x = 0; $x < $numPages; $x++) {
                        $num_padded = sprintf("%02d", $x);
                        $im->setIteratorIndex($x);
                        $im->thumbnailImage(650, 0);
                        $d = $im->getImageGeometry();
                        $im->setImageFormat('png');
                        $im->writeImage($savePath . 'image_' . $num_padded . '.png');
                        $files[$x]['width'] = 650;
                        $files[$x]['height'] = $d['height'];
                        $files[$x]['file'] = 'image_' . $num_padded . '.png';
                    }
                    $im->clear();
                    $im->destroy();

                    if(Storage::disk('dropbox')->put($this->dir . '/PDFProof.pdf', Storage::disk('public')->get($this->dir . '/pdf/' . $this->storageName))) {

                        Storage::delete($realPath);
                        File::deleteDirectory(public_path('/storage/' . $this->dir . '/pdf'));

                        $this->project->active = true;
                        $this->project->save();

                        $this->entry->active = true;
                        $this->entry->pdf_path = Storage::disk('dropbox')->getAdapter()->getTemporaryLink($this->dir . '/PDFProof.pdf');

                        $this->entry->files = json_encode($files);
                        $this->entry->save();

                        $orderVals = $this->project->with('order')->where('id', $this->project->id)->first();

                        if($orderVals->order->notify_users) {
                            $users = UserAssign::with('user')->where('order_id', $this->project->id)->get();
                            foreach($users as $user) {
                                Mail::to($user->user->email)->send(new UserNotify($user->user->id, $this->project));
                            }
                        }
                    }


                } catch(\Exception $e) {
                    report($e);
                }
            }


        }
        catch (Exception $e) {
            report($e);
        }
    }
}
