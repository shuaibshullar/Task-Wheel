let livewireInit = false;
document.addEventListener('livewire:init', () =>    livewireInit = true    );

/**
 * @template dataFromServer
 * @param {string} id
 * @param {(data: dataFromServer) => void} callable
 */
export default function(id, callable)
{
    let livewire = null;
    const getData = () => livewire = Livewire.on(id.toString(), ({data}) => callable(data));

    if (livewireInit) getData();
    else document.addEventListener( 'livewire:init', () => getData() );


    return livewire;
}
