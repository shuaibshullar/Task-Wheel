<?php

use Illuminate\Http\Request;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Component;

new class extends Component
{
    private ?string $data   = null;
    private ?string $id     = null;

    #[Locked]
    public int      $updateTime;

    private function getMethodName(): ?string
    {
        return ! is_null($this->data)
               ? $this->data
               : session()->get('data', null);
    }

    private function getDataId(): ?string
    {
        return ! is_null($this->id)
               ? $this->id
               : session()->get('id', null);
    }

    public function mount(Request $request, App\dataToJs $dataToJs, string $data, string $id, int $update)
    {
        $request->session()->put('data', (string) $data);
        $request->session()->put('id', (string) $id);

        $this->data         = $data;
        $this->id           = $id;
        $this->updateTime   = $update;

        $this->update($dataToJs);
    }

    #[Renderless]
    public function update(App\dataToJs $dataToJs)
    {
        $methodName   = $this->getMethodName();
        $closure      = ! is_null($methodName)
                        ? $dataToJs->getClosure($methodName)
                        : null;


        if (! is_null($closure))
        {
            $this->dispatch(
                $this->getDataId(),
                data: (object) $closure(),
            );
        }
    }
}
?>
<div></div>
<script>
    $wire.$refresh();

    setInterval(
        async () => {
            if (! document.hidden)
            {
                await $wire.update();
            }
        },

        $wire.updateTime * 1000
    );
</script>
