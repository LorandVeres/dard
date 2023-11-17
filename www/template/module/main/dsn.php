<?php include_once '../module/main/modules.Class.php'; ?>
<div id="dsn_1" class="dsn-page">
	<div id="dsn_2" class="dsn-header">
		<div class="span-20 col-f">
			<span class="dsn-project">Project:</span><span id="dsn-project-title">Sandbox</span>
		</div>
		<div class="col-f span-60">
			<div id="dsn-notifications"><p id="dsn-notifications-text">Welcome to the fun</p><p id="dsn-snipet-name"></p></div>
		</div>
		<div class="span-20 col-f">
			<button id="dsn_107" class="dsn-ctl-btn"></button>
			<button id="dsn_108" class="dsn-ctl-btn"></button>
			<button id="dsn_109" class="dsn-ctl-btn"></button>
			<button id="dsn_110" class="dsn-ctl-btn"></button>
		</div>
	</div>
	<div class="section group span-100">
		<div id="dsn_3" class="col-r dsn-side-menu">
		
		<!-- Element actions nav -->
		
		<div class="dsn-node-tree">
			<button id="dsn_100" class="left dsn-node-link" title="Paste element">&lt;&and;&gt;</button>
			<button id="dsn_101" class="left dsn-node-link" title="Go Next sibling up">&#8648;</button>
			<button id="dsn_102" class="left dsn-node-link" title="Go Next sibling down">&#8650;</button>
			<button id="dsn_103" class="left dsn-node-link" title="Go Parent element">[::]</button>
			<button id="dsn_104" class="left dsn-node-link" title="Go First child">&gt;::</button>
			<button id="dsn_105" class="left dsn-node-link" title="Delete selected"><img src="images/icons/g/16/l/delete.png"></button>
			<button id="dsn_106" class="left dsn-node-link" title="Copy this"><img src="images/icons/g/16/l/copy.png"></button>
		</div>
		
		<!-- Element setings and styling collapsible -->
		
		<div id="dsn_4" class="span-100 b-space-15">
		
			<!-- Element name and index -->
		
			<section id="dsn-el-classes">
				<button class="collapse dns-collapse-button">Element</button>
				<div class="collapse-content dsn-element-properties">
					<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
						<div class="col-f span-50"><label class="indent left-5" for="dsn-el-tag-name">Current element :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn_90" name="dsn-el-tag-name" class="span-70" disabled></div>
					</div>
					<div class="section group b-space-10 dsn-dark-field">
						<div class="col-f span-50"><label class="indent left-5" for="dsn-el-index">Index :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn_91" name="dsn-el-index" class="span-20" disabled></div>
					</div>
					<div class="section group b-space-10 dsn-dark-field">
						<div class="col-f span-50"><label class="indent left-5" for="dsn-el-siblings">Siblings :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn_92" name="dsn-el-siblings" class="span-20" disabled></div>
					</div>
					<div class="section group b-space-25 dsn-dark-field">
						<div class="col-f span-50"><label for="dsn-el-child-nodes">Children :</label></div>
						<div class="col-f span-50"><input type="text" id="dsn_93" name="dsn-el-child-nodes" class="span-20" disabled></div>
					</div>
				</div>
			</section>
			
			<!-- Element setings -->
						
			<section id="dsn-el-setings">
				<button class="collapse dns-collapse-button">Settings</button>
				<div class="collapse-content dsn-element-properties">
					
					<!-- Class list -->
					
					<div id="dsn-class-properties" class="space-15">
						<p>Class list :</p>
						<div id="dsn_98" class="dsn-dark-box b-space-20">
							
						</div>
						<div class="section group dsn-attr" data-type="class" data-value="">
							<label for="dsn-el-add-class">Class :</label>
							<input id="dsn_96" type="text" placeholder="Enter class name here" data-type="class" data-value="">
						</div>
					</div>
					
					<!-- Atributes list -->
					<div id="dsn_97" class="span-100" style="overflow:auto;">
						<div class="section group dsn-attr" data-type="attribute" data-value="id">
							<label for="dsn_a_0">Id :</label>
							<input id="dsn_a_0" type="text" placeholder="id here" data-type="attribute" data-value="title">
						</div>
						<div class="section group dsn-attr" data-type="attribute" data-value="title">
							<label for="dsn_a_1">Title :</label>
							<input id="dsn_a_1" type="text" placeholder="title here" data-type="attribute" data-value="title">
						</div>
					</div>
					
					<!-- Custom attributes -->
					
					<section>
						<button class="collapse dsn-naked-collapse-button">Custom attributes</button>
						<div class="collapse-content dsn-element-properties">
							<p class="b-space-10 left-5">Attributes list :</p>
							<div id="dsn_99" class="dsn-dark-box b-space-20">
								
							</div>
							<div class="section group dsn-attr" data-type="attr-name" data-value="">
								<label for="dsn-el-add-class">Attr Name :</label>
								<input id="dsn_95" type="text" placeholder="name here" data-type="attr-name" data-value="">
							</div>
							<div class="section group dsn-attr b-space-15" data-type="attr-value" data-value="">
								<label for="dsn-el-add-class">Attr Value :</label>
								<input id="dsn_94" type="text" placeholder="value here" data-type="attr-value" data-value="">
							</div>
						</div>
					</section>
				</div>
			</section>
			
			<!-- Element styling -->
			
			<section id="dsn-el-style">
			
				<!-- Element whole styling -->
			
				<button class="collapse dns-collapse-button">Style</button>
				<div class="collapse-content dsn-element-properties">
					<div class="c-box b-space-15 t-space-5" style="min-height: 2rem;background-color:#282828;width:80%;border: 1px solid #232323;padding: 0.4rem 0;">
						<div class="section group">
							<div class="col-f span-48">
							<span class="box indent left-5" style="line-height: 2rem;">Apply style</span>
						
							</div>
							<div class="col-f span-48 left-4">
								<button id="dsn_111" class="dsn-ctl-btn"></button>
							</div>
						</div>
						<div class="section group">
							<div class="col-f span-48">
							<span class="box indent left-5" style="line-height: 2rem;">Make as class</span>
						
							</div>
							<div class="col-f span-48 left-4">
								<button id="dsn_111" class="dsn-ctl-btn"></button>
							</div>
						</div>
					</div>
					
				
						<!-- General styling -->
				
						<button class="collapse dsn-naked-collapse-button">General</button>
						<div class="collapse-content dsn-naked-element-properties">
							<div class="section group span-80 c-box b-space-15 dsn-dark-field">
								<div class="col-f span-48">
									<span class="box dsn-el-prop-active t-space-10 b-space-15 indent-05" style="line-height: 1.5rem;">Float<span class="dsn-el-prop-del">×</span></span>
								</div>
								<div class="col-f span-48 left-4">
									<select class="span-100 t-space-10 b-space-15" name="dsn-el-float" id="dsn-el-float">
										<option value=""></option>
										<option value="left">left</option>
										<option value="right">right</option>
									</select>
								</div>
								<div class="col-f span-48 b-space-15">
									<span class="box dsn-el-prop-selected t-space-10 b-space-5 indent-05">Display<span class="dsn-el-prop-del">&times;</span></span>
									<select class="span-100 b-space-10" name="dsn-el-display" id="dsn-el-display">
										<option value="block">block</option>
										<option value="inline" selected>inline</option>
										<option value="inline-block">inline-block</option>
										<option value="flex">flex</option>
										<option value="none">none</option>
									</select>
									<span class="box b-space-5 indent-05">Top</span>
									<input class="span-100 b-space-10" type="text" name="dsn-el-top" placeholder="0">
									<span class="box b-space-5 indent-05">Bottom</span>
									<input class="span-100" type="text" name="dsn-el-bottom" placeholder="0">
								</div>
								<div class="col-f span-48 left-4 b-space-15">
									<span class="box t-space-10 b-space-5 indent-05">Position</span>
									<select class="span-100 b-space-10" name="dsn-el-position" id="dsn-el-position">
										<option value="static">static</option>
										<option value="relative">relative</option>
										<option value="fixed">fixed</option>
										<option value="absolute">absolute</option>
										<option value="sticky">sticky</option>
									</select>
									<span class="box b-space-5 indent-05">Left</span>
									<input class="span-100 b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<span class="box b-space-5 indent-05">Right</span>
									<input class="span-100" type="text" name="dsn-el-right" placeholder="0">
								</div>
							</div>
						</div>
						
						<!-- Dimensions styling -->
						
						<button class="collapse dsn-naked-collapse-button">Dimensions</button>
						<div class="collapse-content dsn-element-properties">
							<div class="dsn-90-box c-box section group t-space-10 b-space-15">
								<div class="col-f span-48 b-space-15">
									<span class="col-f span-100 dsn-el-prop-active t-space-10 b-space-5 indent-05">Width<span class="dsn-el-prop-del">&times;</span></span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Min width</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Max width</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								
								<div class="col-f span-48 left-4 b-space-15">
									<span class="col-f span-100 t-space-10 b-space-5 indent-05">Height</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Min height</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Max height</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Spacing styling -->

						<button class="collapse dsn-naked-collapse-button">Spacing</button>
						<div class="collapse-content dsn-element-properties">
							<span class="box left-10 dsn-el-prop-active t-space-15 b-space-5">Margin<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-15">
								<div class="col-f span-48 b-space-15 t-space-5">
									<span class="col-f span-100 b-space-5 indent-05">Margin left</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Margin top</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4 t-space-5 b-space-15">
									<span class="col-f b-space-5 indent-05">Margin right</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Margin bottom</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
							
							<span class="box left-10 b-space-5 t-space-10">Padding</span>
							<div class="dsn-90-box c-box option group b-space-15">
								<div class="col-f span-48 b-space-15 t-space-10">
									<span class="col-f span-100 b-space-5 indent-05">Padding left</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Padding top</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4 b-space-15 t-space-10">
									<span class="col-f span-100 b-space-5 indent-05">Padding right</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Padding bottom</span>
									<input class="span-4-of-6-f col-f" type="text" name="dsn-el-right" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
						</div>
					
						<!-- Typography styling -->
					
						<button class="collapse dsn-naked-collapse-button">Typography</button>
						<div class="collapse-content dsn-element-properties">
							<div class="dsn-90-box c-box section group space-15">
								<div class="col-f span-48 b-space-15">
									<span class="box span-100 dsn-el-prop-active indent-05" style="line-height: 1.5rem;">Text align<span class="dsn-el-prop-del">×</span></span>
								</div>
								<div class="col-f span-48 left-4 b-space-15">
									<select class="box span-100" name="dsn-el-float" id="dsn-el-float">
										<option value="left">left</option>
										<option value="right">right</option>
										<option value="center">center</option>
										<option value="justify">justify</option>
									</select>
								</div>
								<div class="col-f span-48 b-space-15">
									<span class="col-f span-100 t-space-10 b-space-5 indent-05">Font size</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Font Family</span>
									<select class="span-100 b-space-10" name="dsn-el-font-family" id="dsn-el-font-family">
										<option value="Arial, sans-serif">Arial</option>
										<option value="Verdana, sans-serif">Verdana</option>
										<option value="Times New Roman, serif">Times New Roman</option>
										<option value="Tahoma, sans-serif">Tahoma</option>
										<option value="Georgia, serif">Georgia</option>
										<option value="Garamond, serif">Garamond</option>
										<option value="courier new, monospace">Courier New</option>
										<option value="Brush Script MT, cursive">Brush Script MT</option>
										<option value="Helvetica">Helvetica</option>
										<option value="Impact">Impact</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Font Weight</span>
									<select class="span-100" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="100">Thin (Hairline)</option>
										<option value="200">Extra Light (Ultra Light)</option>
										<option value="300">Light</option>
										<option value="400">Normal (Regular)</option>
										<option value="500">Medium</option>
										<option value="600">Semi Bold (Demi Bold)</option>
										<option value="700">Bold</option>
										<option value="800">Extra Bold (Ultra Bold)</option>
										<option value="900">Black (Heavy)</option>
									</select>
								</div>
								
								<div class="col-f span-48 b-space-15 left-4">
									<span class="col-f span-100 t-space-10 b-space-5 indent-05">Line height</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6 col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Letter spacing</span>
									<input class="span-4-of-6-f col-f b-space-10" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Color</span>
									<input class="span-4-of-6-f col-f" type="text" style="display:inline;" name="dsn-el-right" value="#c99a16">
									<input class="span-2-of-6-f col-f" type="color" style="display:inline;" name="dsn-el-right" value="#c99a16">
								</div>
							</div>
						</div>
					
						<!-- Decorations styling -->
					
						<button class="collapse dsn-naked-collapse-button">Decoration</button>
						<div class="collapse-content dsn-element-properties">
							
							<!-- Opacity -->
							
							<div class="span-80 c-box option group space-15">
								<div class="col-f b-space-15 span-100">
									<span class="col-f pad-5 span-80">Opacity</span>
									<input class="box span-20 right" type="text" placeholder="0.5">
								</div>
								<input type="range" min="1" max="100" value="50" class="dsn-slider" id="myRange">
							</div>
							
							<!-- Borders -->
							
							<span class="box span-100 dsn-el-prop-active t-space-10 b-space-5 left-5 indent">Borders<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-10">
								<div class="col-f span-48">
									<span class="col-f span-100 b-space-5 indent-05">Width</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4">
									<span class="col-f span-100 b-space-5 indent-05">Style</span>
									<select class="span-100 col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="none">none</option>
										<option value="solid">solid</option>
										<option value="inherit">inherit</option>
										<option value="hidden">hidden</option>
										<option value="dotted">dotted</option>
										<option value="dashed">dashed</option>
										<option value="double">double</option>
										<option value="groove">groove</option>
										<option value="ridge">ridge</option>
										<option value="inset">inset</option>
										<option value="outset">outset</option>
										<option value="initial">initial</option>
									</select>
								</div>
								<span class="col-f span-100 b-space-5 indent-05">Color</span>
								<div class="span-100 col-f">
									<input class="span-6-of-7-f col-f" type="text" name="dsn-el-right" value="#c99a16">
									<input class="span-1-of-7-f col-f" type="color" name="dsn-el-right" value="#c99a16">
								</div>
							</div>
							
							<!-- Border radius -->
							
							<span class="line-box dsn-el-prop-selected t-space-10 b-space-5 left-5 indent">Border radius<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-10">
								<div class="col-f span-48">
									<span class="col-f span-100 b-space-5 indent-05">Top left</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Bottom left</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
								<div class="col-f span-48 left-4">
									<span class="col-f span-100 b-space-5 indent-05">Top right</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
									<span class="col-f span-100 b-space-5 indent-05">Bottom Right</span>
									<input class="span-4-of-6-f b-space-10 col-f" type="text" name="dsn-el-left" placeholder="0">
									<select class="span-2-of-6-f col-f" name="dsn-el-margin-unit" id="dsn-el-margin-unit">
										<option value="px">px</option>
										<option value="%">%</option>
										<option value="rem">rem</option>
										<option value="em">em</option>
										<option value="vh">vh</option>
										<option value="vw">vw</option>
									</select>
								</div>
							</div>
							
							<!-- Background -->
							
							<span class="line-box dsn-el-prop-selected t-space-10 b-space-5 left-5 indent">Backgound<span class="dsn-el-prop-del">×</span></span>
							<div class="dsn-90-box c-box option group b-space-10">
								<span class="col-f span-100 b-space-5 indent-05">Background Color</span>
								<div class="span-100 col-f b-space-10">
									<input class="span-6-of-7-f col-f" type="text" style="display:inline;" name="dsn-el-right" value="#c99a16">
									<input class="span-1-of-7-f col-f" type="color" style="display:inline;" name="dsn-el-right" value="#c99a16">
								</div>
								<span class="col-f span-100 b-space-5 indent-05">Background Image</span>
								<div class="span-100 col-f">
									<input class="span-100 left" type="text" style="display:inline;" name="dsn-el-right" placeholder="url('img_tree.gif')">
								</div>
							</div>
						</div>
						
						<!-- Other styling -->
					
						<button class="collapse dsn-naked-collapse-button">Other</button>
						<div class="collapse-content dsn-element-properties">
							
						</div>
						
						<!-- Flex styling -->
					
						<button class="collapse dsn-naked-collapse-button">Flex</button>
						<div class="collapse-content dsn-element-properties">
							
						</div>
					
					<div class="dsn-element-properties-last"></div>
				</div>
			</section>
		</div>
		
		<!-- SETTINGS -->
		
		<section id="dsn_6" class="span-100 b-space-15">
			<button class="collapse dns-collapse-button">General</button>
			<div class="collapse-content dsn-element-properties">
				<div class="section group b-space-15 dsn-dark-field">
					<button id="dsn-335" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-336" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-338" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-339" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-340" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-341" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-342" title="" class="dsn-snipet-btn"></button>
					<button id="dsn-343" title="" class="dsn-snipet-btn"></button>
				</div>
			</div>
			<button class="collapse dns-collapse-button">Snippet</button>
			<div class="collapse-content dsn-element-properties">
				<div class="section group span-100">
					<button id="dsn-326" class="dsn-snipet-tab col-f span-1-of-3-f">Snippet</button>
					<button id="dsn-327" class="dsn-snipet-tab col-f span-1-of-3-f">Download</button>
					<button id="dsn-328" class="dsn-snipet-tab col-f span-1-of-3-f">Stash</button>
				</div>
				
				<!-- Snipet settings -->
				
				<div class="dsn-snipet-tab-content">
					<div class="section group b-space-15 dsn-dark-field">
						<button id="dsn-307" title="Unlock fields" class="dsn-307 dsn-snipet-btn"></button>
						<button id="dsn-308" title="Empty fields" class="dsn-308 dsn-snipet-btn"></button>
						<button id="dsn-310" title="Save" class="dsn-snipet-btn"></button>
						<button id="dsn-311" title="Add new" class="dsn-snipet-btn"></button>
						<button id="dsn-313" title="Clear screen" class="dsn-snipet-btn"></button>
					</div>
				</div>
				<div class="dsn-snipet-tab-content">
					<div class="section group b-space-15 dsn-dark-field">
						<button id="dsn-324" title="Unlock fields" class="dsn-307 dsn-snipet-btn"></button>
						<button id="dsn-325" title="Empty fields" class="dsn-308 dsn-snipet-btn"></button>
						<button id="dsn-309" title="Download" class="dsn-snipet-btn"></button>
						<button id="dsn-323" title="Apply extension" class="dsn-snipet-btn"></button>
						<button id="dsn-312" title="Edit this" class="dsn-snipet-btn"></button>
					</div>
				</div>
				<div class="dsn-snipet-tab-content">
					<div class="section group b-space-15 dsn-dark-field">
						<button title="Unlock fields" class="dsn-307 dsn-snipet-btn"></button>
						<button title="Empty fields" class="dsn-308 dsn-snipet-btn"></button>
						<button id="dsn-329" title="Stash selected" class="dsn-snipet-btn"></button>
						<button id="dsn-330" title="Stash all" class="dsn-snipet-btn"></button>
						<button id="dsn-331" title="Create new" class="dsn-snipet-btn"></button>
						<button id="dsn-332" title="Restore" class="dsn-snipet-btn"></button>
					</div>
				</div>
				<div class="section group b-space-10 dsn-dark-field"> 
					<div class="col-f span-40"><label class="indent left-5" for="dsn-el-tag-name">Name :</label></div>
					<div class="col-f span-60"><input type="text" id="dsn-314" class="span-80" disabled></div>
				</div>
				<div class="section group b-space-10 dsn-dark-field">
					<div class="col-f span-40"><label class="indent left-5" for="dsn-el-index">Type :</label></div>
					<div class="col-f span-60">
						<select id="dsn-315" class="span-80" disabled>
						</select>
					</div>
				</div>
				<div class="section group b-space-10 dsn-dark-field">
					<div class="col-f span-40"><label class="indent left-5" for="dsn-el-siblings">Status :</label></div>
					<div class="col-f span-60">
						<select id="dsn-316" class="span-80" disabled>
						</select>
					</div>
				</div>
				<div class="section group b-space-25 dsn-dark-field" style="visibility: hidden;">
					<div class="col-f span-40"><label for="dsn-el-child-nodes">Project :</label></div>
					<div class="col-f span-60">
						<select id="dsn-317" class="span-80" disabled>
						</select>
					</div>
				</div>
				
				<!-- Enviro settings -->
				
				<div class="section group span-80 dsn-vary-properties dsn-dark-field c-box">
					<div class="section group span-100 t-space-10 b-space-20">
						<span class="col-f span-80 indent-05" style="line-height: 1.5rem;">Screen width</span>
						<span class="col-f span-20"><input id="dsn-304" type="text" class="span-70 right" style="text-align:right" placeholder="px"></span>
						
					</div>
					<div class="section group span-100" style="height: 2.6rem;">
						<span class="col span-1-of-6"><button id="dsn-334" title="Responsive mode" class="dsn-enviro-btn dsn-responsive-mode"></button></span>
						<span class="col span-1-of-6"><button class="dsn-enviro-btn " style="visibility: hidden;"></button></span>
						<span class="col span-1-of-6"><button id="dsn-333" title="full screen" class="dsn-enviro-btn dsn-monitor-btn "></button></span>
						<span class="col span-1-of-6"><button id="dsn-303" title="working size" class="dsn-enviro-btn dsn-monitor-btn "></button></span>
						<span class="col span-1-of-6"><button id="dsn-302" title="tablet" class="dsn-tablet-btn dsn-enviro-btn"></button></span>
						<span class="col span-1-of-6"><button id="dsn-301" title="phone" class="dsn-enviro-btn dsn-smartphone-btn "></button></span>
					</div>
					<div class="span-100 t-space-10"><span class="box indent" style="line-height: 1.5rem;">Background color</span></div>
					<div class="section group span-100 c-box b-space-10" style="border: 1px solid #111;">
						<input id="dsn-305" class="span-6-of-7-f col-f" type="text" style="display:inline;border:none;" name="dsn-el-right" value="#c99a16">
						<input id="dsn-306" class="span-1-of-7-f col-f" type="color" style="display:inline;border:none;" name="dsn-el-right" value="#c99a16">
					</div>
				</div>
				<div id="dsn-318" class="dsn-node-tree">
					<button class="left dsn-node-link-25" title="After this" data-pos="afterend">after</button>
					<button class="left dsn-node-link-25" title="Before this" data-pos="beforebegin">before</button>
					<button class="left dsn-node-link-25" title="Inside at begin" data-pos="afterbegin">in at begin</button>
					<button class="left dsn-node-link-25" title="Inside at end" data-pos="beforeend">in at end</button>
				</div>
			</div>
			
			<!-- Project settings -->
			
			<button class="collapse dns-collapse-button">Project</button>
			<div class="collapse-content dsn-element-properties">
				<div class="section group span-90 c-box dsn-dark-field t-space-15">
					<div class="section group span-80 col-f">
						<span class="col-f span-40" style="line-height: 1.5rem;">Set project</span>
						<select id="dsn-319" class="col-f span-60">
							<option value=""></option>
						</select>
					</div>
					<div class="col-f span-20">
						<button id="dsn-321" class="dsn-ctl-btn" style="margin: 0;"></button>
					</div>
				</div>
				<div class="section group span-90 c-box dsn-dark-field dsn-vary-properties space-15 pad-15">
					<div class="section group span-80 col-f">
						<span class="col-f span-40" style="line-height: 1.5rem;">New project</span>
						<input id="dsn-320" type="text" class="col-f span-60">
					</div>
					<div class="col-f span-20">
						<button id="dsn-322" class="dsn-ctl-btn" style="margin: 0;"></button>
					</div>
					
						
				</div>
			</div>
		</section>
		
		
		<!--  HTML tags and blocks ---->
		
		<section id="dsn_7" class="span-100 b-space-15">
		
			<!--  GENERAL ELEMENTS HTML tags and blocks ---->
			
			<button class="collapse dns-collapse-button">HTML Elements</button>
			<div class="collapse-content dsn-element-properties">
				<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
					<div class="col-f span-50"><label class="indent left-5" for="dsn-200">Element Name :</label></div>
					<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70"></div>
				</div>
				<div class="section group b-space-10 dsn-dark-field">
					<div class="col-f span-50"><label class="indent left-5" for="dsn-201">Repeated :</label></div>
					<div class="col-f span-50"><input type="text" id="dsn-201" name="dsn-el-index" class="span-20" value="1"></div>
				</div>
				<div class="dsn-90-box c-box dsn-dark-field">
					<label class="center" for="dsn-202" style="margin: 15px 32%;width:68%">Dummy text here</label>
					<select id="dsn-202" class="span-60 c-box">
						<option value=""></option>
						<option value="X-small">X small</option>
						<option value="small">small</option>
						<option value="medium">medium</option>
						<option value="large">large</option>
						<option value="X-large">X large</option>
						<option value="XX-large">XX large</option>
					</select>
				</div>
				<div class="section group t-space-10 dsn-dark-field">
					<div class="t-space-10 c-box span-100"><input type="button" id="dsn-203" value="ADD" class="t-space-10 c-box span-50" style="margin:0 25%"></div>
					<div class="span-100" style="height: 1rem;"></div>
				</div>
			</div>
			<div id="dsn-204" class="dsn-node-tree">
				<button id="dsn-206" class="left dsn-node-link-25" title="After this" data-pos="afterend">after</button>
				<button id="dsn-207" class="left dsn-node-link-25" title="Before this" data-pos="beforebegin">before</button>
				<button id="dsn-208" class="left dsn-node-link-25" title="Inside at begin" data-pos="afterbegin">in at begin</button>
				<button id="dsn-209" class="left dsn-node-link-25" title="Inside at end" data-pos="beforeend">in at end</button>
				<input id="dsn-205" type="hidden" name="layout-position" value="afterend">
			</div>
			<!--  LAYOUT HTML tags and blocks ---->
			
			<button class="collapse dns-collapse-button">Layout</button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Layout templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
			
			<!--  TEXT HTML tags and blocks ---->
			
			<button class="collapse dns-collapse-button">Text </button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 t-space-25 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
			
			<!--  FORMS HTML tags and blocks ---->
			
			<button class="collapse dns-collapse-button">Forms</button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Form templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 t-space-25 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
			
			<!--  LISTS HTML tags and blocks ---->
			
			<button class="collapse dns-collapse-button">Lists</button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Lists templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 t-space-25 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
			
			
			<button class="collapse dns-collapse-button">Table</button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Table templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 t-space-25 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
			
			
			<button class="collapse dns-collapse-button">Media</button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Media templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 t-space-25 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
			
			
			<button class="collapse dns-collapse-button">Code</button>
			<div class="collapse-content dsn-element-properties">
				<button class="collapse dsn-naked-collapse-button">Code templates</button>
				<div class="collapse-content dsn-element-properties">
					<div class="span-100 t-space-25 b-space-25">
						<div class="section group b-space-10 t-space-15 dsn-dark-field"> 
							<div class="col-f span-50"><label class="indent left-5" for="dsn_200">Element Name :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn-200" name="dsn-el-tag-name" class="span-70" disabled></div>
						</div>
						<div class="section group b-space-10 dsn-dark-field">
							<div class="col-f span-50"><label class="indent left-5" for="dsn_201">Index :</label></div>
							<div class="col-f span-50"><input type="text" id="dsn_201" name="dsn-el-index" class="span-20" disabled></div>
						</div>
					</div>
				</div>
				
				<div class="span-100 t-space-25 b-space-25">
					<div class="section group span-100 t-space-25 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Header</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Section</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Main</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Aside</button>
						</div>
					</div>
					<div class="section group span-100 b-space-5">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Article</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Footer</button>
						</div>
					</div>
					<div class="section group span-100 b-space-25">
						<div class="col-f span-48 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Div</button>
						</div>
						<div class="col-f span-48 left-4 b-space-5 dsn-layout">
							<button data-larq="" data-laex="" data-ltxt="" data-pos="">Nav</button>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!--  END OF HTML tags and blocks ---->
		
		</div>
		<div id="dsn_5" class="col-r dsn-body" contenteditable="true">
		<div>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
			<p> A <b>funny</b> text for <i>test <span>little inside the fun</span></i></p>
		</div>
		<div id="wetest">
			<p> A <b>funny bunny with id</b> text for <i>test <span>little inside the fun</span></i></p>
		</div>
		<div><input id="test" type=""></div>
		</div>
	</div>
</div>