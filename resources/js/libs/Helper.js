class Helper {
    omit(obj, keysToExclude) {
        return Object.fromEntries(
        Object.entries(obj).filter(([key]) => !keysToExclude.includes(key))
        );
    }
    
    addScriptLfmOverride() {
        const scriptElement = document.getElementById('lfm-override-script');
        if (scriptElement) {
            return;
        }
        
        const script = document.createElement('script');
        script.src = '/vendor/lfm-override.js';
        script.id = 'lfm-override-script';
        document.head.appendChild(script);
    }
    
    receiveMessageLFM(event) {
        let file = null;
        
        // if (event.origin !== window.location.origin) return;
        if (event.data.type === 'lfm-selected') {
            // console.log(event.data);
            file = event.data.items?.[0] || { url: event.data.url };
            
        }
        
        return file ?? false;
    }
    
    receiveMessageLFMMutifile(event) {
        let files = null;
        
        if (event.origin !== window.location.origin) return;
        if (event.data.type === 'lfm-selected') {
            files = event.data.items;
            
        }
        
        return files ?? false;
    }
    
}

export default new Helper;