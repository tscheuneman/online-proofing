<?php

namespace Tjscheuneman\Proofing\Helpers;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotifyRevision;

use Storage;
use File;
use Imagick;

use Tjscheuneman\Proofing\Entry;
use App\Project;
use App\UserAssign;

use Mockery\Exception;

class ConvertPDFSecondary implements ShouldQueue
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
                        $d = $im->getImageGeometry();
                        if($d['width'] > 3000 || $d['height'] > 3000) {
                            $im->thumbnailImage(3000, 3000, true);
                            $d = $im->getImageGeometry();
                        }
                        $im->writeImage($savePath . 'image_' . $num_padded . '.png');

                        $im->thumbnailImage(200, 0);
                        $im->setImageFormat('png');
                        $im->writeImage($savePath . 'thumb_' . $num_padded . '.png');

                        $files[$x]['width'] = $d['width'];
                        $files[$x]['height'] = $d['height'];
                        $files[$x]['file'] = 'image_' . $num_padded . '.png';
                    }
                    $im->clear();
                    $im->destroy();

                    $orderVals = $this->project->with('order')->where('id', $this->project->id)->first();

                    $proj = $this->project->with('admin_entries')->where('id', $this->project->id)->first();

                    $numCounter = 0;
                    foreach($proj->admin_entries as $ent) {
                        if($ent->admin) {
                            $numCounter++;
                        }
                    }

                    $proj_year = date('Y', strtotime($this->project->created_at));
                    $proj_month = date('F', strtotime($this->project->created_at));
                    $projectPath = 'projects/' . $proj_year . '/' . $proj_month . '/' . $orderVals->order->job_id . '/' . $this->project->project_name;

                    if(Storage::disk('dropbox')->put($projectPath . '/' . $this->project->project_name . '-PDFProof-'.$numCounter.'.pdf', Storage::disk('public')->get($this->dir . '/pdf/' . $this->storageName))) {

                        Storage::delete($realPath);
                        File::deleteDirectory(public_path('/storage/' . $this->dir . '/pdf'));

                        $this->project->active = true;
                        $this->project->save();

                        $this->entry->active = true;
                        $this->entry->pdf_path = $projectPath . '/' . $this->project->project_name . '-PDFProof-'.$numCounter.'.pdf';

                        $this->entry->files = json_encode($files);
                        $this->entry->save();


                        if($orderVals->order->notify_users) {
                            $users = UserAssign::with('user')->where('order_id', $orderVals->order->id)->get();
                            foreach($users as $user) {
                                Mail::to($user->user->email)->send(new UserNotifyRevision($user->user->id, $this->project));
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
