
function App(props) {
    console.log(props);
    function handleClick() {
        alert();
    }
    return (
      <div className="App">
        <h1>This is an Demo</h1>
        <button  onClick={()=>{
          props.onclick();
        //tsPropertySignature.
         //handleClick();
          }}>Test</button>
        </div>
    
  );
}

export default App;
