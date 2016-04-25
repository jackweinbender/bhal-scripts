var fs=require('graceful-fs');
var Firebase = require('firebase');

var dir='./data/';
var data={};
var entries = new Firebase('https://bhal.firebaseio.com/entrys/');
var definitions = new Firebase('https://bhal.firebaseio.com/defs/');
var name = ''

fs.readdir(dir,function(err,files){
    if (err) throw err;
    files.forEach(function(file){
        fs.readFile(dir+file,'utf-8',function(err,content){
          if (err) throw err;
          var headerInfo = JSON.parse(content).header;
          var itemDefs = JSON.parse(content).definitions

          var entry = {
            entry: headerInfo.word,
            meta: {
                entryNum:   headerInfo.entry,
                letter:     headerInfo.letter,
                strongs:    headerInfo.strongs,
                page:       headerInfo.page
            },
            root: '',
            note: '',
            raw: JSON.parse(content).header
          };

          var newEntry = entries.push(entry);
          var entryID = newEntry.name();

          var def = {
            belongs_to: entryID,
            content: itemDefs
          }

          var newDef = definitions.push(def);

        });
    });
});
