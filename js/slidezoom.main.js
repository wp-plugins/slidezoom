        function FuncAddUploadControl() {
            var ctl = document.getElementById('lblUploadID');
            var container = document.getElementById('sz_ctlcontainer');
            if (!ctl || !container) alert('Debug Message : No Controls.');
            var newNode = ctl.cloneNode(true);
			container.appendChild(newNode);
		}
		
        function ChangeOutput(mode) {
            switch (mode) {
                case 'raw':
                    document.getElementById('rawbox').setAttribute('style', 'display:display');
                    document.getElementById('htmlbox').setAttribute('style', 'display:none');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:none');
                    break;
                case 'html':
                    document.getElementById('rawbox').setAttribute('style', 'display:none');
                    document.getElementById('htmlbox').setAttribute('style', 'display:display');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:none');
                    break;
                case 'bbcode':
                    document.getElementById('rawbox').setAttribute('style', 'display:none');
                    document.getElementById('htmlbox').setAttribute('style', 'display:none');
                    document.getElementById('bbcodebox').setAttribute('style', 'display:display');
                    break;
            }
        }
		
        function toggle_mode(e) {
            switch (e) {
                case 'file':
                    document.getElementById('sz_mode').value = 'file';
                    document.getElementById('sz_file_button').setAttribute('style', 'display:display');
                    document.getElementById('sz_ctlcontainer').setAttribute('style', 'display:display');
                    document.getElementById('sz_zipcontainer').setAttribute('style', 'display:none');
                    break;
                case 'zip':
                    document.getElementById('sz_mode').value = 'zip';
                    document.getElementById('sz_file_button').setAttribute('style', 'display:none');
                    document.getElementById('sz_ctlcontainer').setAttribute('style', 'display:none');
                    document.getElementById('sz_zipcontainer').setAttribute('style', 'display:display');
                    break;
            }
        }