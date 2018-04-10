<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Project;
use App\Entry;

use File;

use Intervention\Image\ImageManager;

use Mockery\Exception;

class UserEntry implements ShouldQueue
{
    public $tries = 1;
    public $timeout = 500;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dir, $comments, $files, $entry;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comments, $files, Entry $entry, $dir)
    {
        $this->comments = $comments;
        $this->files = $files;
        $this->entry = $entry;
        $this->dir = $dir;
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
                    $files = array();
                    $num_padded = 0;
                    $savePath = public_path('/storage/' . $this->dir . '/images/');
                    foreach($this->files as $file) {
                        $manager = new ImageManager();
                        $img = $manager->make(file_get_contents($file))->save($savePath . 'image_' . $num_padded . '.png');

                        $files[$num_padded]['width'] = $img->width();
                        $files[$num_padded]['height'] = $img->height();
                        $files[$num_padded]['file'] = 'image_' . $num_padded . '.png';
                        $num_padded++;
                    }

                    $this->entry->active = true;
                    $this->entry->files = json_encode($files);
                    $this->entry->user_notes = json_encode($this->comments);
                    $this->entry->save();
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