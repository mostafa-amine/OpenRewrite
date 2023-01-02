<?php

namespace App\Http\Livewire\Rewriter;

use Livewire\Component;
use App\Services\Openai;
use App\Services\OpenaiService;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class Rewrite extends Component implements HasForms
{

    use InteractsWithForms;

   // Text tone
   public $text_tone = [
    'formal' => 'Text Formal',
    'casual' => 'Text Casual',
    'persuasive' => 'Text Persuasive',
    'emotive' => 'Text Emotive',
   ];
   public $selected_t_tones = [];

   // Text Structure
   public $text_structure = [
    'concise' => 'Text concise',
    'coherent' => 'Text coherent',
    'logical' => 'Text logical'
   ];
   public $selected_t_structure = [];

   // Text Focus
   public $t_focus =  '';

   // Correcting errors or mistakes
   public $text_errors = [
    'spelling' => 'Correct spelling',
    'grammar' => 'Correct grammar',
    'punctuation' => 'Correct punctuation',
   ];
   public $selected_f_errors = [];

   // User Input
   public $user_input = '';

   // User Output
   public $user_output = '';

   // Result Status
   public $result_status = true;

   // Rewritng Tasks
   public $tasks = '';

   // Wizard Skippable
   public $skippable = false;


   public function mount()
   {
        $this->form->fill([
            'user_output' => $this->user_output,
        ]);
   }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('Your Text ')
                ->icon('heroicon-o-user')
                ->description('Write The Text To rewrite')
                ->schema([
                    Textarea::make('user_input')
                    ->label('Text')
                    ->required()
                ]),

                Step::make('Rewriting Tasks')
                ->icon('heroicon-o-pencil')
                ->description('Specify Your Text Rewriting Tasks')
                ->schema([
                    Grid::make(2)
                    ->schema([
                        // Text Tone
                    Select::make('selected_t_tones')
                    ->multiple()
                    ->placeholder('Select Text Tones')
                    ->label('Text Tones')
                    ->options($this->text_tone)
                    ->searchable(),

                    // Text Structure
                    Select::make('selected_t_structure')
                    ->multiple()
                    ->placeholder('Select Text Structure')
                    ->label('Text Structure')
                    ->options($this->text_structure)
                    ->searchable(),

                    // Text Focus
                    TextInput::make('t_focus')
                    ->label('Text Focus'),

                    // Correct Errors
                    Select::make('selected_f_errors')
                    ->multiple()
                    ->placeholder('Select Erros types to fix')
                    ->label('Correct Errors')
                    ->options($this->text_errors)
                    ->searchable(),
                    ])
                ]),

                Step::make('Result')
                ->icon('heroicon-o-chat')
                ->schema([
                    TextArea::make('user_output')
                    ->label('Result')
                    ->hidden($this->result_status)
                ]),


            ])
            ->submitAction(new HtmlString('<button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-700"> Generate </button>'))
            ->skippable($this->skippable),

        ];
    }
    public function rewritngTasks()
    {

        if(!empty($this->selected_t_tones)){
            $this->tasks .= ' Make The tone of the text : ' ;
            $this->tasks .= implode(',' , $this->selected_t_tones);
        }


        if(!empty($this->selected_t_structure))
        {
            $this->tasks .= ', And make the structure of the text : ';
            $this->tasks .= implode(',' , $this->selected_t_structure);

        }

        if(!empty($this->selected_f_errors)){
            $this->tasks .= ', And Fix The ';
            $this->tasks .= implode(',' , $this->selected_f_errors);

        }

        if($this->t_focus !== '')
        {
            $this->tasks .= ', And Make the text more focused on the term : ' . $this->t_focus;
        }
    }

    public function rewrite()
    {

        $this->skippable = true;

        $this->tasks .= "Rewrite this : '";
        $this->tasks .= $this->user_input . "', ";

        $this->rewritngTasks();
        dd($this->tasks);
        $result = OpenaiService::generate(env('OPENAI_API_KEY') , $this->tasks);

        $this->user_output = $result;

        $this->result_status = false;
    }



    public function render()
    {
        return view('livewire.rewriter.rewrite');
    }
}
