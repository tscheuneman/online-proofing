<?php

namespace Tjscheuneman\Proofing\Helpers;

use File;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

use App\Project;
use App\AdminAssign;
use App\Mail\AdminNotify;

use Intervention\Image\ImageManager;
use Mockery\Exception;

use Tjscheuneman\Proofing\Entry;


class UserEntry implements ShouldQueue
{
    public $tries = 1;
    public $timeout = 500;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dir, $comments, $files, $entry, $project;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comments, $files, Entry $entry, $dir, Project $project)
    {
        $this->comments = $comments;
        $this->files = $files;
        $this->entry = $entry;
        $this->dir = $dir;
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if(File::makeDirectory( public_path('/storage/' . $this->dir . '/images'), 0777, true)) {
                try {
                    $returnFiles = array();
                    $num_padded = 0;
                    $savePath = public_path('/storage/' . $this->dir . '/images/');
                    foreach($this->files as $file) {
                        $manager = new ImageManager();
                        $img = $manager->make(file_get_contents($file))->save($savePath . 'image_' . $num_padded . '.png');

                        $returnFiles[$num_padded]['width'] = $img->width();
                        $returnFiles[$num_padded]['height'] = $img->height();
                        $returnFiles[$num_padded]['file'] = 'image_' . $num_padded . '.png';
                        $num_padded++;
                    }

                    $this->entry->active = true;
                    $this->entry->files = json_encode($returnFiles);
                    $this->entry->user_notes = json_encode($this->comments);
                    $this->entry->save();

                    $orderVals = $this->project->with('order')->where('id', $this->project->id)->first();



                    if($orderVals->order->notify_admins) {
                        $users = AdminAssign::with('admin.user')->where('order_id', $orderVals->order->id)->get();
                        foreach($users as $user) {
                            Mail::to($user->admin->user->email)->send(new AdminNotify($user->admin->user->id, $this->project));
                        }
                    }

                } catch(Exception $e) {
                    report($e);
                }
            }
        }
        catch (Exception $e) {
            report($e);
        }
    }
}
