#target "indesign"
var DataRoot = 'd:\\Work\\TV=works\\ktv-changer\\rules\\';
/**
    скрипт для централизованной обработки сеток тв-программы.
    Предполагаемые функции:
    пользователь выделяет фрейм с исходной тв-программой, вызывает скрипт
    Скрипт определяет к какому каналу относится текст и вносит изменения по списку.

    Задача сделать переносимый сборник правил для возможности синхронизации.
    
    */

var myDoc = app.activeDocument;



var tvchmsg = {
        nothingToDo: 'Выделите фрейм и повторите попытку',
        unsupportedSelection: 'Выбранный элемент не поддерживается. \nВыделите текстовый фрейм и повторите попытку',
        unsupportedTVChanenl:'Не удалось опознать необходимую тв-программу.\nРабота завершена, изменений не внесено'
 }

var tv_changer_config = {
    root : DataRoot,
    list : 'channels.txt',
    commonList:'0all',
    commonListInit:'000-init.txt',
    commonListFinaly:'999-finaly.txt',
    itemListPreCommon:'000-pre-common.txt',
    itemListWork:'500-main.txt',
    itemListPostCommon:'999-post-common.txt',
    RulesDir:DataRoot+'0Rules\\',
    ChangeRuleTitle:'заменить'
}


/** класс для работы с изменениями тв-прогармы
    в качестве параметра конструктора получает выделеный объект
    предполагается, что это текстовый фрейм с тв-прогарммой */

function _tv_changer (selectedObject) {
    /** функция читает список известных каналов
        список  имеет формат: title%tab%parten
        где title - название- рограммы понятное для компьютера
        a parten - подстрока, по которой определяется канал.
        При налчии в исходном объекте партена считается, что ответ найден.
        */
    this.Determinate_channel  = function  (ObjectToWork) {
        var chFile = File ( tv_changer_config.root + tv_changer_config.list );
        chFile.open('r'); 
        var testString = ObjectToWork.contents;
        
        try {
            while ( ! chFile.eof ) {
                var line = chFile.readln();
                var tmpdata = line.split('\t');
                var tvch = tmpdata[0];
                var searchparten = tmpdata[1];
                    if (testString.indexOf(searchparten) != -1 ) {
                        //    alert ("founded, " + tvch);
                        return tvch;
                    }
                /* alert (tvch);
                    alert (searchparten); */
            }
        } catch(err) {
                throw(err);
        } finally {
            chFile.close();
        }
            throw (tvchmsg.unsupportedTVChanenl);
    }

   /** !!! Внимание одно встроенное правило, остальные выдераются из целевой дириктории
Заменить[tab]ЧТО#НА-что       
       */
    this.ReadTemplateRule = function (RuleName, RuleParameter) {
        /** Читает шаблон автозамен, вставляет необходимые изменения */
        var RulesDir = tv_changer_config.RulesDir;
        var ruleFile = File (RulesDir+RuleName+'.txt');
        var RuleFind;
       var RuleChange;
       var tmpdata;
       
        if (RuleName.toLowerCase() ==  tv_changer_config.ChangeRuleTitle) {
                    tmpdata = RuleParameter.split('#');
                    RuleFind = tmpdata[0];
                    RuleChange = tmpdata[1];
                    if (RuleFind == undefined) return null;
                    if (RuleChange == undefined) RuleChange = '';
                    
                    var retval = { find: RuleFind, change: RuleChange,  findParams:"", changeParams:"" };
                    return retval;
        }
        
        try {
                ruleFile.open('r'); 
                var RuleFind = ruleFile.readln();
                var RuleChange  = ruleFile.readln();
                var RuleFindParams = ruleFile.readln();
                var RuleChangeParms =ruleFile.readln();
                if (RuleFind == undefined) return null;
                 if (RuleChange == undefined) RuleFind = '';
                 if (RuleFindParams == undefined) RuleFindParams = "";
                 if (RuleChangeParms == undefined) RuleChangeParms = "";
                
                RuleFind = RuleFind.replace(/-=-/g, RuleParameter);
                RuleChange = RuleChange.replace(/-=-/g, RuleParameter);
            
        } catch(err) {
                throw(err);
        } finally {
                ruleFile.close();
        }
        var retval = { find: RuleFind, change: RuleChange, findParams: RuleFindParams , changeParams: RuleChangeParms };
        return retval;
    }

    this.ParseChannelRule = function  (line) {
            /** TODO: отпарсить строку с задачей на замену */
            var tmpdata = line.split('\t');
            var ruleName = tmpdata[0];
            var ruleParameter = tmpdata[1];
            if (ruleName == undefined) return;
            if (ruleParameter == undefined) ruleParameter = '';

            var retval = this.ReadTemplateRule(ruleName, ruleParameter);
            return retval;
    }
    
    this.ApplyFindChange = function (ObjectToWork, parameters) {
        var needForRevert = false;

         if (parameters.find == '') { return; }
        app.findGrepPreferences.findWhat = parameters.find;
        app.changeGrepPreferences.changeTo =parameters.change;
        if (parameters.findParams != '') {
            app.findGrepPreferences.appliedParagraphStyle = myDoc.paragraphStyles.item(parameters.findParams);
            needForRevert = true;
        }
        if (parameters.changeParams != '') {
            app.changeGrepPreferences.appliedParagraphStyle = myDoc.paragraphStyles.item(parameters.changeParams);
            needForRevert = true;
        }
        

          ObjectToWork.changeGrep();
          if (needForRevert ){
            app.findGrepPreferences = NothingEnum.nothing; 
            app.changeGrepPreferences = NothingEnum.nothing;
        }

       }
    this.ApplyChangeList = function (object, changeListName) {
        var chListFile = File (changeListName);
        
         try {
               chListFile.open('r');
            while ( ! chListFile.eof ) {
                
                var line = chListFile.readln();
                var Rule = this.ParseChannelRule(line);
                // alert (Rule);
                this.ApplyFindChange(object, Rule);
                /** TODO: do changes */
                
                
            }
        } catch(err) {
                throw(err);
        } finally {
                chListFile.close();
        }
        
        
    }

    /** Основная функция - обработчик текста */
    this.DoIt = function(objectToWork) {
        var commonFolder = tv_changer_config.root+tv_changer_config.commonList+'\\';
        var channelFolder = tv_changer_config.root+this.parameters.tv_program+'\\';

         this.ApplyChangeList(objectToWork, channelFolder+tv_changer_config.itemListPreCommon);
         this.ApplyChangeList(objectToWork, commonFolder+tv_changer_config.commonListInit);
         this.ApplyChangeList(objectToWork, channelFolder+tv_changer_config.itemListWork);
         this.ApplyChangeList(objectToWork, commonFolder+tv_changer_config.commonListFinaly);
         this.ApplyChangeList(objectToWork, channelFolder+tv_changer_config.itemListPostCommon);


      /*  tv_changer_config.root */
    }
  
  this.parameters = {
        /* определяемое имя тв-программы */
        tv_program : this.Determinate_channel(selectedObject),
    };
    /** _tv_changer_constructor return */
    return this;
}
/** головная функция скрипта */
function tvch_main() {
    try {
        app.findGrepPreferences = NothingEnum.nothing; 
        app.changeGrepPreferences = NothingEnum.nothing;

        var objectToWork = app.activeDocument.selection[0];
        if (objectToWork != '[object TextFrame]') throw (tvchmsg.unsupportedSelection);
        if (objectToWork == undefined) throw (tvchmsg.nothingToDo);
        
        var myChanger = new _tv_changer(objectToWork);
        myChanger.DoIt(objectToWork);
        
} catch (err) {
            alert (err+".");
            return;
    }


 }


/** заглушка для возможности инклюда этого файла. 
    Если переменная tvchNeedForRun установлена в false, то запуска головной функции не будет */
if (tvchNeedForRun == undefined) {
    var tvchNeedForRun = true;
}
tvchNeedForRun && tvch_main();