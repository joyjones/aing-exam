var configs = {
    testMode: location.href.indexOf('#testmode') > 0,
	qnUrl: 'http://oyh711q4x.bkt.clouddn.com/',
	thumbTail: {
		projLogo: '?imageView2/1/w/100/h/100',
        questOption: '?imageView2/1/w/235/h/160',
		banner: ''
	},
    apiHostUrl: 'http://exam-api.aing.net.cn/',
    webHostUrl: 'http://exam.aing.net.cn/exams/',
    chatInterval: 400,
    storageKey: 'aing-eval-player-uid',
    scales: {
        evalImage: 420.0 / 750.0,
        bannerImage: 408.0 / 750.0
    }
};
Vue.prototype.configs = configs;

var main = (function(){
	var scrn = {size: {w:0, h:0}, aspect: 0, scale: 1};
	if (window.location.href.indexOf('aing.net.cn') < 0)
		configs.apiHostUrl = 'http://api.aiyin.com/';

	return {
		player: null,
		vues: {},
		init: function(){
			scrn.size.w = $(window).width();
			scrn.size.h = $(window).height();
			scrn.aspect = scrn.size.w / scrn.size.h;
			scrn.scale = scrn.size.w / 640;
            if (configs.testMode)
                document.title = document.title + ' 测试模式';
			var me = this;

            me.vues.home = new Vue({
                el: 'section#home',
                data: {
            		visible: false,
                	tabIndex: -1,
					projects: [],
                    banners: [],
                    swiper: null,
                },
                computed: {
                    bannerHeight: function(){
                        return Math.floor($(window).width() * configs.scales.bannerImage);
                    }
                },
                watch: {
                    projects: function(val){
                        (this.tabIndex == -1) && this.clickTab(0);
                    },
                    banners: function(val){
                        if (this.swiper)
                            return;
                        setTimeout(function(){
                            this.swiper = new Swiper('.swiper-container', {
                                pagination: '.swiper-pagination',
                                paginationClickable: true,
                                autoplay: 3000
                            })
                        }, 200);
                    }
                },
                methods: {
                	enter: function(){
                        var self = this;
                        var reg = /\bprj=(\d+)/;
                        var r = window.location.href.match(reg);
                        if (r != null){
                            var enterId = r[1];
                            for (var i = 0; i < self.projects.length; ++i){
                                if (self.projects[i].id == enterId){
                                    me.vues.chat.enter(self.projects[i]);
                                    return;
                                }
                            }
                        }
                		self.visible = true;
                        var w = $(window).width();
                        var h = this.bannerHeight;
                        configs.thumbTail.banner = '?imageView2/1/w/' + w + '/h/' + h;

                        me.httpAjax('banner/index', function(resp){
                            self.banners = resp;
                        });
                	},
                    clickBanner: function(url){
                        window.location.href = url;
                    },
                	clickTab: function(index){
                		this.tabIndex = index;
                        this.projects.sort(function(p1, p2){
                            if (index == 1)
                                return p2.playerCount - p1.playerCount;
                            return p2.utime - p1.utime;
                        });
                	},
                	clickListItem: function(prj){
                		console.log(prj);
                		me.vues.home.visible = false;
                		me.vues.chat.enter(prj);
                	}
                }
            });
            me.vues.chat = new Vue({
            	el: 'section#chat',
            	data: {
            		visible: false,
            		project: null,
            		starting: false,
            		items: [],
            		itemsInQueue: [],
            		tickFunc: null,
            		options: [],
            		curQuest: null,
            		optionBtnWidth: '33%'
            	},
            	methods: {
            		enter: function(prj){
            			this.visible = true;
            			this.project = prj;
            			this.items.length = 0;
            			this.itemsInQueue.length = 0;
            			this.options.length = 0;
            			this.curQuest = null;
            			this.sayTitle();
            		},
            		addChat: function(type, text, callbk){
            			this.itemsInQueue.push({
            				type: type,
            				text: text,
            				callbk: callbk
            			});
            		},
            		addImage: function(type, img, callbk){
            			this.itemsInQueue.push({
            				type: type,
            				img: img,
            				callbk: callbk
            			});
            		},
            		sayTitle: function(){
            			var self = this;
            			!this.tickFunc && (this.tickFunc = setInterval(function(){
            				if (self.itemsInQueue.length == 0)
            					return;
            				var i = self.itemsInQueue[0];
	            			self.items.push(i);
            				self.itemsInQueue.splice(0, 1);
            				setTimeout(function(){
								window.scrollTo(0, document.body.scrollHeight);
								i.callbk && i.callbk();
            				}, 100);
            			}, configs.chatInterval));

            			this.addChat(1, this.project.dialog);
            			this.addImage(1, this.project.logo, function(){
	            			self.starting = true;
            			});
            		},
                    getHeadImageQ: function(){
                        var img = this.project.headimg_q;
                        if (img && img.length > 0)
                            return configs.qnUrl + img;
                        return 'img/touxiang.png';
                    },
                    getHeadImageA: function(){
                        var img = this.project.headimg_a;
                        if (img && img.length > 0)
                            return configs.qnUrl + img;
                        return 'img/quce.png';
                    },
            		start: function(){
            			var self = this;
            			if (self.curQuest != null)
            				return;
        				self.addChat(2, '开始');
            			self.options = [];
            			var url = 'players/' + me.player.id + '?expand=beginEvaluation';
            			url += '&projectId=' + self.project.id;
            			me.httpAjax(url, function(resp){
            				resp = resp.beginEvaluation;
			            	console.log(resp);
        					me.player.eval_id = resp.eval_id;
        					self.beginQuestion(resp.firstQuestion, resp.options);
            			});
            		},
            		beginQuestion: function(q, opts){
            			var self = this;
    					this.curQuest = q;
            			this.addChat(1, q.topic);
            			this.starting = false;
            			var chs = 'ABCDEFGHIJKLMN', idx = 0;
            			for (var i = 0; i < opts.length; ++i){
            				var ch = chs[idx++];
            				var cb = null;
            				if (i == opts.length - 1){
            					cb = function(){
									self.options = opts;
            					};
            				}
            				if (q.type == 1)
            					this.addChat(1, ch + '. ' + opts[i].content, cb);
            				else {
            					this.addChat(1, ch + '. ');
            					this.addImage(1, opts[i].content, cb);
            				}
            				opts[i].mark = ch;
            				opts[i].index = i;
            			}
            			if (opts.length > 0) {
	            			var w = Math.floor(100 / opts.length);
	            			this.optionBtnWidth = w + "%";
	            		}
            		},
            		clickOption: function(opt){
            			var self = this;
            			self.options.length = 0;
        				self.addChat(2, opt.mark);
        				if (!me.player.eval_id){
        					console.error('"me.player.eval_id" is invalid!');
        					return;
        				}
            			var url = 'players/' + me.player.id;
            			url += '?expand=answerQuestion';
            			url += '&evalId=' + me.player.eval_id;
            			url += '&optionId=' + opt.id;
			            me.httpAjax(url, function(resp){
            				resp = resp.answerQuestion;
			            	console.log(resp);
			            	if (resp.result)
                                self.finishTest(resp.result);
                            else{
                                if (resp.nextQuestion == null)
                                    alert('error: empty questions before ending result!');
                                else if (resp.nextOptions == null)
                                    alert('error: empty question options before ending result!');
                                else
			            		   self.beginQuestion(resp.nextQuestion, resp.nextOptions);
                            }
			            });
            		},
            		finishTest: function(result){
            			var self = this;
            			self.options.length = 0;
            			self.addChat(1, '正在为你分析结果...');
            			setTimeout(function(){
	        				me.vues.chat.visible = false;
	        				self.tickFunc && clearInterval(self.tickFunc);
	        				self.tickFunc = null;
		            		me.vues.eval.enter(self.project, result);
            			}, 2000);
            		}
            	}
            });
            me.vues.eval = new Vue({
            	el: 'section#evaluation',
            	data: {
            		visible: false,
            		project: null,
            		caption: '',
            		score: 0,
                    showScore: true,
            		briefs: [],
            		desc: '',
                    image: '',
                    qrcodeImg: '',
            		shareTipVisible: false
            	},
                computed: {
                    bannerHeight: function(){
                        return Math.floor($(window).width() * configs.scales.evalImage);
                    }
                },
            	methods: {
            		enter: function(proj, result){
            			this.visible = true;
            			this.project = proj;
            			this.caption = proj.caption;
            			this.score = result.score;
                        this.showScore = proj.show_score;
            			this.briefs = result.eval.brief.split(',');
            			this.desc = result.eval.desc;
                        this.image = result.eval.image;
                        this.qrcodeImg = proj.qrcode_img;
                        if (result.eval.share_title && result.eval.share_title.length > 0){
                            weixin.fillShare($.extend({}, weixin.info, {
                                type: 'link',
                                title: result.eval.share_title,
                                link: window.location.href
                            }));
                        }
            		},
            		clickHome: function(){
            			this.visible = false;
                        window.location.href = configs.webHostUrl;
            		},
            		clickRetry: function(){
            			this.visible = false;
	            		me.vues.chat.enter(this.project);
            		},
            		clickShare: function(){
            			this.shareTipVisible = true;
            		},
            		clickShareTip: function(){
            			this.shareTipVisible = false;
            		}
            	}
            });

            var url = 'app/login';
            var savedId = localStorage.getItem(configs.storageKey);
            if (savedId)
                url += '?uid=' + savedId;
            me.httpAjax(url, function(resp){
            	me.player = resp.player;
                localStorage.setItem(configs.storageKey, me.player.unique_id);
				var url = 'projects?expand=questions,evaluations,playerCount';
				me.httpAjax(url, function(resp){
	                me.vues.home.projects = resp;
                    me.vues.home.enter();
				});
            });

            // me.vues.eval.enter({caption: '你在宝宝心中的位置', show_score: 1}, {score: 95, eval: {brief: '老虎型妈妈,（权威、支配、独断）', desc:'95分实际上就是物美价廉，实际上就是物美价廉，实际上就是物美价廉，实际上就是物美价廉，实际上就是物美价廉', image: 'u5lrOlTGzWQPCLw_7-1d6FfczBdAe8ER.jpeg'}});
        },
		httpAjax: function(url, callbk, errbk){
            $.ajax({
                url: configs.apiHostUrl + url,
                type: 'get',
                dataType: 'json',
                error: function(e,e1,e2){
                    console.log(e);
                    errbk && errbk();
                },
                success: function(resp){
                    console.log(resp);
                    callbk && callbk(resp);
                }
            });
		}
	}
})();

$(function(){
	// if (!/Windows\s+Phone/.test(navigator.userAgent) && !/MicroMessenger/.test(navigator.userAgent)){
	// }else{
		weixin.init();
		main.init();
	  // 	if (/iPhone/.test(navigator.userAgent)){
			// $('body').css('font-family', 'Heiti SC');
	  // 	}
	// }
});